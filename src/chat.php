<?php
namespace MyApp;
include "../includes/chat_file_register.php";
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $clients;
    protected $conn_map;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->conn_map = array();
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";

    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;

        //to register id of a user to his connection
        $id = json_decode($msg, true);
        if(isset($id["id_setter"])){
            $id = $id["id_setter"];
            $this->conn_map += [$id => $from->resourceId];

            echo sprintf('Setting %d ID for Connection %d' . "\n"
            , $id, $from->resourceId, $numRecv == 1 ? '' : 's');

            return;
        }

        //sending message to specific connection
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        checkForFile($msg);

        $data = json_decode($msg, true);
        $receiver_id = $data["messages"][0]["user_to"]["id"];

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                if(isset($this->conn_map[$receiver_id]) && $this->conn_map[$receiver_id] == $client->resourceId){
                    $client->send($msg);
                }
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $key = array_search($conn->resourceId, $this->conn_map);
        unset($this->conn_map[$key]);
        echo "User {$key} has disconnected from connection {$conn->resourceId}\n";

        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}