<?=($_COOKIE['role'] == 'admin') ? '<a href="settings.php">
      <div class="color-switcher-toggle animated pulse infinite">
        <i class="material-icons">settings</i>
      </div>
    </a>' : '';?>