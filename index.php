<?php
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
    <title>BR 24 Lite</title>
  </head>
  <body>
    <h1>BR 24 Lite</h1>
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