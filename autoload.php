// Test 3

class ChartServer implements Ratchet\MessageComponentInterface
{
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(Ratchet\ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "New connection has arrived! ({$conn->resourceId})\n";
    }

    public function onClose(Ratchet\ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(Ratchet\ConnectionInterface $conn, Exception $e) {
        echo "An error has occurred Bro: {$e->getMessage()}\n";
        $conn->close();
    }

    public function onMessage(Ratchet\ConnectionInterface $from, $msg) {
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // Send the received message to all clients except the sender
                $client->send($msg);
            }
        }
    }
}
