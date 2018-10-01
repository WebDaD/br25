<?php

$type = (isset($_GET["type"])) ? $_GET["type"] : "list";
if($type != "list" && $type != "cards") {
  die("Type ".$type." not known");
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
libxml_use_internal_errors(true);

$feed_url = "https://nachrichtenfeeds.br.de/rdf/boards/QXAPkQJ";
$content = file_get_contents($feed_url);
$content = preg_replace('/&(?!;{6})/', '&amp;', $content);
$content = preg_replace('/dc:date/', 'dcdate', $content);
$content = preg_replace('/mp:image/', 'mpimage', $content);
$content = preg_replace('/mp:data/', 'mpdata', $content);
$content = preg_replace('/mp:alt/', 'mpalt', $content);
$xml = simplexml_load_string($content);

if ($xml === false) {
  echo "Laden des XML fehlgeschlagen\n";
  foreach(libxml_get_errors() as $error) {
      echo "\t", $error->message;
  }
  die();
}
// TODO: Add Info from channel into page
// TODO: CARDS: Add dc:type and image and mp:topline

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
      <?php if($type == "list"): ?>
      <ul>
        <?php foreach($xml->item as $entry): ?>
        <li>
          <a href='<?php echo $entry->link;?>' title='<?php echo $entry->title;?>' target="_blank">
            [
            <?php echo date_format(date_create($entry->dcdate), "d.m.y hh:ii");?> ]
            <?php echo $entry->title;?>
          </a>
          <br />
          <p>
            <?php echo $entry->description;?>
          </p>
        </li>
        <?php endforeach;?>
      </ul>
      <?php else: ?>
      <div class="card-columns">
        <?php foreach($xml->item as $entry): ?>
        <div class="card">
          <img class="card-img-top" src="<?php echo $entry->mpimage[0]->mpdata;?>" alt="<?php echo $entry->mpimage[0]->mpalt;?>">
          <div class="card-body">
            <h5 class="card-title"><?php echo $entry->title;?></h5>
            <p class="card-text"><?php echo $entry->description;?></p>
          </div>
          <div class="card-body">
          <a class="card-link" href='<?php echo $entry->link;?>' title='<?php echo $entry->title;?>' target="_blank">
              Weiterlesen ...
            </a>
          </div>
          <div class="card-footer">
            <small class="text-muted"><?php echo date_format(date_create($entry->dcdate), "d.m.y hh:ii");?></small>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
      <hr />
      <p>
        <a href="<?php echo $feed_url;?>">RSS-Feed</a>
      </p>
    </div>
  </body>

  </html>