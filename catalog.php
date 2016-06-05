<?php
include("inc/functions.php");

$pageTitle = "Full Catalog";
$section = null;
$items_per_page = 8;

if (isset($_GET["cat"])) {
    if ($_GET["cat"] == "books") {
        $pageTitle = "Books";
        $section = "books";
    } else if ($_GET["cat"] == "movies") {
        $pageTitle = "Movies";
        $section = "movies";
    } else if ($_GET["cat"] == "music") {
        $pageTitle = "Music";
        $section = "music";
    }
}

if (isset($_GET["pg"])) {
  $current_page = filter_input(INPUT_GET,"pg",FILTER_SANITIZE_NUMBER_INT);
}
if (empty($current_page)) {
  $current_page = 1;
}

$total_items = get_catalog_count($section);
$total_pages = ceil($total_items / $items_per_page);

//limit results in redirection
$limit_results = "";
if (!empty($section)) {
  $limit_results = "cat=" . $section . "&";
}

//redirect too-large page numbers to the last page
if ($current_page > $total_pages) {
  header("location:catalog.php?"
          . $limit_results
          . "pg=".$total_pages);
}
//rediresct too-small page numbers to first page
if ($current_page < 1) {
  header("location:catalog.php?"
          . $limit_results
          . "pg=1");
}
//determine the offset (number of items to skip) for the current page
//for example: on page 3 with 8 items per page, the offset would be 16
$offset = ($current_page - 1) * $items_per_page;


if (empty($section)) {
  $catalog = full_catalog_array();
} else {
  $catalog = category_catalog_array($section);
}

include("inc/header.php"); ?>

<div class="section catalog page">

    <div class="wrapper">

        <h1><?php
        if ($section != null) {
            echo "<a href='catalog.php'>Full Catalog</a> &gt; ";
        }
        echo $pageTitle; ?></h1>

        <ul class="items">
            <?php
            foreach ($catalog as $item) {
                echo get_item_html($item);
            }
            ?>
        </ul>

    </div>
</div>

<?php include("inc/footer.php"); ?>
