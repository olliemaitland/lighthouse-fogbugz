<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ollie Maitland
 * Date: 09/02/13
 * Time: 16:29
 * To change this template use File | Settings | File Templates.
 */

namespace LightFogz\Lighthouse;

use Guzzle\Http\Client as BaseClient;

class Client extends BaseClient
{
    public function __construct(\LightFogz\Entities\Configuration $config)
    {
        $this->config = $config;
        $baseUrl = $this->config->get("lighthouse-url");

        $params = array ();
        parent::__construct($baseUrl, $params);

        $this->setDefaultHeaders(array ("X-LighthouseToken" => $this->config->get("lighthouse-token")));
    }

    /**
     * Retrieve a list of projects
     *
     * @return Project[]
     */
    public function getProjects()
    {
        $request = $this->get(sprintf("projects.xml"));

        $response = $request->send();
        $xml = $response->xml();

        $projects = array ();
        foreach ($xml->project as $projectTag) {
            $project = new Project;
            $project->name  = (string)$projectTag->name;
            $project->projectId  = (string)$projectTag->id;
            $project->permalink  = (string)$projectTag->permalink;
            $project->created = new \DateTime((string)$projectTag->{'created-at'});
            $projects[] = $project;
        }
        return $projects;
    }

    /**
     * @return Tickets[]
     */
    public function getTickets()
    {
        $tickets = array ();
        foreach ($this->getProjects() as $project) {
            $tickets = array_merge($tickets, $this->getTicketsForProject($project));
        }
        return $tickets;
    }

    /**
     * Retrieve tickets for a project from a date
     *
     * @param Project $project
     * @return array
     */
    public function getTicketsForProject(Project $project)
    {
        $time = strtotime($this->config->get("lighthouse-date"));
        if (!$time && $project->created) {
            $time = $project->created->format("U");
        }

        $query = sprintf('not-state:closed created:"%s"', date("Y-m-d", $time));
        $request = $this->get(sprintf("projects/%s-%s/tickets.xml?q=%s", $project->projectId, $project->permalink, $query));

        $response = $request->send();
        $xml = $response->xml();

        $tickets = array ();

        $latestDate = $project->created;

        foreach ($xml->ticket as $ticketTag) {
            $ticket = new Ticket;
            $ticket->title   = (string)$ticketTag->title;
            $ticket->created = new \DateTime((string)$ticketTag->{'created-at'});
            $ticket->creator = (string)$ticketTag->{'creator-name'};
            $ticket->body    = (string)$ticketTag->{'latest-body'};
            $tickets[] = $ticket;

            if ($latestDate < $ticket->created) {
                $latestDate = $ticket->created;
            }
        }

        // set the latest date
        $this->config->set("lighthouse-date", $latestDate->format("Y-m-d"));
        $this->config->save();

        return $tickets;
    }
}