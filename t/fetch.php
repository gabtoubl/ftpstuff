<?php

function cancube($dom, $page = 0) {
  $url = "https://cancube.ca/collections/all?sort_by=price-descending";
  if ($page != 0)
    $url .= "&page=".$page;
  $dom->LoadHTML(file_get_contents($url));
  $xpath = new DOMXPath($dom);

  if ($page == 0) {
    $pagination = $xpath->query("//*[contains(@class, 'pagination')]")[0];
    $nodes = $xpath->query(".//*[contains(@class, 'page')]", $pagination);
    $lastPage = $nodes[$nodes->length-1]->nodeValue;
    echo $lastPage;
  }
  else {
    $products = $xpath->query("//*[contains(@class, 'product-card__info')]");
    $puzzles = [];
    foreach ($products as $product) {
      $productName = $xpath->query(".//*[contains(@class, 'product-card__name')]", $product)[0]->nodeValue;
      $productPrice = $xpath->query(".//*[contains(@class, 'product-card__price')]", $product)[0];
      $productPrice = floatval(substr($xpath->query(".//*[contains(@class, 'money')]", $productPrice)[0]->nodeValue, 1));
      $puzzles[] = array('name' => $productName,
                         'price' => $productPrice,
                         'currency' => 'CAD',
                         'store' => 'cancube');
    }
    echo json_encode($puzzles);
  }
}

function cubingoutloud($dom, $page = 0) {
  $url = 'https://www.cubingoutloud.com/collections/all?sort_by=price-descending';
  if ($page != 0)
    $url .= "&page=".$page;
  $dom->LoadHTML(file_get_contents($url));
  $xpath = new DOMXPath($dom);

  if ($page == 0) {
    $pagination = $xpath->query("//*[contains(@class, 'pagination-custom')]")[0];
    $nodes = $pagination->getElementsByTagName('a');
    $lastPage = $nodes[$nodes->length-2]->nodeValue;
    echo $lastPage;
  }
  else {
    $products = $xpath->query("//*[contains(@class, 'grid-link-thumbnail')]");
    $puzzles = [];
    foreach ($products as $product) {
      $productName = $xpath->query(".//*[contains(@class, 'grid-link__title')]", $product)[0]->nodeValue;
      $productName = preg_replace('/[^a-z0-9\-\']/i', ' ', $productName);
      $productPrice = $xpath->query(".//*[contains(@class, 'grid-link__meta')]", $product)[0];
      $productPrice = floatval(substr($product->getElementsByTagName('strong')[0]->nodeValue, 1));
      $puzzles[] = array('name' => $productName,
                         'price' => $productPrice,
                         'currency' => 'CAD',
                         'store' => 'cubingoutloud');
    }
    echo json_encode($puzzles);
  }
}

function speedcubescanada($dom, $page = 0) {
  $url = 'https://speedcubescanada.com/collections/all?sort_by=price-descending';
  if ($page != 0)
    $url .= "&page=".$page;
  $dom->LoadHTML(file_get_contents($url));
  $xpath = new DOMXPath($dom);

  if ($page == 0) {
    $pagination = $xpath->query("//*[contains(@class, 'pagination-custom')]")[0];
    $nodes = $pagination->getElementsByTagName('a');
    $lastPage = $nodes[$nodes->length-2]->nodeValue;
    echo $lastPage;
  }
  else {
    $products = $xpath->query("//*[contains(@class, 'product-grid-item')]");
    $puzzles = [];
    foreach ($products as $product) {
      $productName = $product->getElementsByTagName('p')[0]->nodeValue;
      $productPrice = $xpath->query(".//*[contains(@class, 'product-item--price')]", $product)[0];
      $productPrice = floatval(substr(explode(' ', $xpath->query(".//*[contains(@class, 'money')]", $productPrice)[0]->nodeValue)[0], 1));
      $puzzles[] = array('name' => $productName,
                         'price' => $productPrice,
                         'currency' => 'CAD',
                         'store' => 'speedcubescanada');
    }
    echo json_encode($puzzles);
  }
}

function cubezz($dom, $page = 0) {
  $url = 'http://www.cubezz.com/search.php?sort=shop_price&order=DESC&outstock=1';
  if ($page != 0)
    $url .= "&page=".$page;
  $dom->LoadHTML(file_get_contents($url));
  $xpath = new DOMXPath($dom);

  if ($page == 0) {
    $pagination = $xpath->query("//*[contains(@class, 'pagetextxx')]")[0]->nodeValue;
    preg_match('/into(\d+)/', $pagination, $matches);
    $lastPage = $matches[1];
    echo $lastPage;
  }
  else {
    $products = $xpath->query("//*[contains(@class, 'g_pro1')]");
    $puzzles = [];
    foreach ($products as $product) {
      $productName = $xpath->query(".//*[contains(@class, 'gaddtext')]", $product)[0];
      $productName = $productName->getElementsByTagName('a')[0]->nodeValue;
      $productPrice = $xpath->query(".//*[contains(@class, 'gaddprice')]", $product)[0];
      $productPrice = floatval(explode(' ', $xpath->query(".//*[contains(@class, 'my_shop_price')]", $productPrice)[0]->nodeValue)[1]);
      $puzzles[] = array('name' => $productName,
                         'price' => $productPrice,
                         'currency' => 'USD',
                         'store' => 'cubezz');
    }
    echo json_encode($puzzles);
  }
}

ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0)');
libxml_use_internal_errors(true);
$dom = new DOMDocument;
$dom->preserveWhiteSpace = false;
$page = isset($_GET['page']) ? intval($_GET['page']) : 0;
if (isset($_GET['site'])) {
  $_GET['site']($dom, $page);
}

?>

