<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
libxml_use_internal_errors(true);

$feed_url = "https://nachrichtenfeeds.br.de/rdf/boards/QXAPkQJ";
$content = file_get_contents($feed_url);
$content = preg_replace('/&(?!;{6})/', '&amp;', $content);
$xml = simplexml_load_string($content);

if ($xml === false) {
  echo "Laden des XML fehlgeschlagen\n";
  foreach(libxml_get_errors() as $error) {
      echo "\t", $error->message;
  }
  die();
}
// TODO: Add Info from channel into page
// TODO: Add dc:type and image and mp:topline
// TODO: Extra Page with Images + Text (parse Link)

//TODO: bootstrap.php (bootstrap.css via cdn!) with cards as a design

?>
<html>

<head>
  <title>BR 25</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
    crossorigin="anonymous">
</head>

<body>
  <div class="container">
    <h1>BR 25</h1>
    <pre>
      <?php echo $xml[0]; ?>
    </pre>
    <ul>
      <?php foreach($xml->channel->item as $entry): ?>
      <li>
        <a href='<?php echo $entry->link;?>' title='<?php echo $entry->title;?>' target="_blank">
          [
          <?php echo $entry["dc:date"];?>]
          <?php echo $entry->title;?>
        </a>
        <br />
        <p>
          <?php echo $entry->description;?>
        </p>
      </li>
      <?php endforeach;?>
    </ul>
    <hr />
    <p>
      <a href="<?php echo $feed_url;?>">RSS-Feed</a>
    </p>
  </div>
</body>

</html>