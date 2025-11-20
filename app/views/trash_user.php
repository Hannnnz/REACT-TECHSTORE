<?php
include APP_DIR.'views/templates/header.php';
?>
<body style="background-image: url('<?= base_url();?>public/resources/jenshin.gif'); background-size: cover; background-repeat: no-repeat; background-attachment: fixed;">
    <div id="app">
    <?php
    include APP_DIR.'views/templates/nav.php';
    ?>  
    <main class="mt-3 pt-3">
        <div class="container">
            <div class="row justify-content-center">
                <div class="characters-header">
    <h1>Genshin Trash Bin</h1>

    <div class="header-actions">
        <form action="<?=site_url('trash-user');?>" method="get" class="search-form">
            <?php $q = isset($_GET['q']) ? $_GET['q'] : ''; ?>
            <input class="form-control" name="q" type="text" placeholder="Search">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>
</div>

    <div class="table-container">
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Image</th>
        <th>Character</th>
        <th>Element</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach(html_escape($all) as $char): ?>
      <tr>
        <td><?= $char['id']; ?></td>
        <td>
          <img src="<?= base_url().'uploads/'.$char['pic']; ?>" alt="<?= $char['name']; ?>">
        </td>
        <td><?= $char['name']; ?></td>
        <td><?= $char['class']; ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

    <?php
	echo $page;?>
            </div>
        </div>
    </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
</body>
</html>