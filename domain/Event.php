<?php
/**
 * Encapsulated version of a dbs entry.
 */
class Event {
    private $id;
    private $name;
    private $abbr;
    private $type;
    private $startDate;
    private $startTime;
    private $endTime;
    private $endDate;
    private $description;
    private $capacity;
    private $location;
    private $completed;
    private $access;


    function __construct($id, $name, $abbr, $type, $startDate, $startTime, $endTime, $endDate, $description, $capacity, $location, $access, $completed) {
        $this->id = $id;
        $this->name = $name;
        $this->abbr = $abbr;
        $this->type = $type;
        $this->startDate = $startDate;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->endDate = $endDate;
        $this->description = $description;
        $this->capacity = $capacity;
        $this->location = $location;
        $this->access = $access;
        $this->completed = $completed;
        
    }

    function getID() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getAbbr() {
        return $this->abbr;
    }

    function getStartDate() {
        return $this->startDate;
    }

    function getStartTime() {
        return $this->startTime;
    }

    function getEndTime() {
        return $this->endTime;
    }

    function getEndDate() {
        return $this->endDate;
    }

    function getDescription() {
        return $this->description;
    }

    function getLocation() {
        return $this->location;
    }

    function getCapacity() {
        return $this->capacity;
    }

    function getCompleted() {
        return $this->completed;
    }

    function getEventType(){
        return $this->type;
    }

    function getAccess(){
        return $this->access;
    }

}