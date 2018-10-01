<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$feed_url = "https://nachrichtenfeeds.br.de/rdf/boards/QXAPkQJ";
$content = file_get_contents($feed_url);
$xml = new SimpleXmlElement($content);

// TODO: Add Info from channel into page
// TODO: Add dc:type and image and mp:topline
// TODO: Extra Page with Images + Text (parse Link)

//TODO: bootstrap.php (bootstrap.css via cdn!) with cards as a design

?>
<html>
  <head>
    <title>BR 25</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  </head>
  <body>
    <h1>BR 25</h1>
    <ul>
      <?php foreach($xml->channel->item as $entry): ?>
        <li>
          <a href='<?php echo $entry->link;?>' title='<?php echo $entry->title;?>' target="_blank">
            [<?php echo $entry["dc:date"];?>] <?php echo $entry->title;?>
          </a>
          <br/>
          <p><?php echo $entry->description;?></p>
        </li>
      <?php endforeach;?>
    </ul>
    <hr/>
    <p>
      <a href="<?php echo $feed_url;?>">RSS-Feed</a>
    </p>
  </body>
</html>