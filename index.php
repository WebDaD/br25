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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    
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
            <?php echo date_format(date_create($entry->dcdate), "d.m.Y H:i");?> ]
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
            <h5 class="card-title">
              <?php echo $entry->title;?>
            </h5>
            <p class="card-text">
              <?php echo $entry->description;?>
            </p>
          </div>
          <div class="card-body">
            <a class="card-link" href='<?php echo $entry->link;?>' title='<?php echo $entry->title;?>' target="_blank">
              Weiterlesen ...
            </a>
          </div>
          <div class="card-footer">
            <small class="text-muted">
              <?php echo date_format(date_create($entry->dcdate), "d.m.Y H:i");?>
            </small>
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
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
      crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
      crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
      crossorigin="anonymous"></script>
  </body>

  </html>