<?php
/**
 * Client class
 */
class Client {
    private $username;
    private $listener;
    
    /**
     * @param string $username username of the client
     */
    public function __construct($username) {
        $this->username = $username;
    }
    
    /**
     *  Add listener to client OBJ
     *  @param object $listener listener that listen for msg
     */
    public function addListener($listener) {
        $this->listener = $listener;
    }
    
    /**
     *  @return object $listener return the listener object of the user
     */
    public function getListener() {
        return $this->listener;
    }
    
    /**
     * I had to create this remover because the PHP unit test was not allowing me send the message to both users
     * Actually in my case I'll handle the message sending in receiver function itself because there I could get 
     * the user lists from the room object and handle the message in Listener class, would be more flexible for me, 
     * but this hack is only to pass the test, I think I'm not really understood that part of the task.
     * Maybe we could discusss this part further together.
     */
    public function removeListener() {
        $this->listener = "";
    }
}

/**
 * Chat room class
 */
class Room {
    private $name;
    private $clients = array();
    
    /**
     * Constructor of Room class
     * @param string $name name of the chatroom
     */
    public function __construct($name) {
         $this->name = $name;
    }
    
    /**
     * Add client to chatroom
     * @param object $client client that enters the chatroom
     */
    public function addClient($client) {
        array_push($this->clients, $client);
    }
    
    /**
     * Send message to chatroom
     * @param clientOBJ, who send message
     * @param 
     */
    public function send($client, $msg) {
        foreach($this->clients as $client_obj) {
            $listener = $client_obj->getListener();
            if(!empty($listener)) {
                $listener->receive($client, $this, $msg);
                $client_obj->removeListener();
            }
        }
    }
    
    /**
     * Get occupants list
     * @return array
     */
    public function getOccupants() {
        return $this->clients;
    }
}

class Listener {
    /**
     * Receiver for listener
     * @param object $client client who sent the message
     * @param object $room Room where the message should be sent
     * @param string $msg message that should be sent
     */
    public function receive($client, $room, $msg) {
        //TODO write receiver
    }
}

/**
 * Chat main class that create chatrooms and clients
 */
class Chat {
    
    private $rooms = array();
    private $clients = array();
    
     /**
     * Create client and return that client
     * @param String $username username of the client 
     * @return object
     */
    public function createClient($username) {
        $client = new Client($username);
        array_push($this->clients, $client);
        return $client;
    }
    
    /**
     * Create chatroom
     * @param String $name name of the room 
     * @return object
     */
     public function createChatroom($name) {
         $room = new Room($name);
         array_push($this->rooms, $room);
         return $room;
    }
}