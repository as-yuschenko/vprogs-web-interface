<?php


if (!extension_loaded('sockets')) {
    die('The sockets extension is not loaded.');
}

$socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
if (!$socket) die('Unable to create AF_UNIX socket');

$path = dirname(__FILE__)."/thread1.sock";

echo ($path."\n");

$msg = "Hello!";
$len = strlen($msg);



if (socket_connect($socket, $path)) echo "Conn+\n";
else echo "Conn-\n";

socket_send($socket, $msg, $len, NULL);

socket_recv($socket, $msg, 20, NULL);

echo ($msg);
/*
$msg = "Mess";
$len = strlen($msg);
// at this point 'server' process must be running and bound to receive from serv.sock
$bytes_sent = 
if ($bytes_sent == -1)
        die('An error occured while sending to the socket');
else if ($bytes_sent != $len)
        die($bytes_sent . ' bytes have been sent instead of the ' . $len . ' bytes expected');
        */
?>	

