<?php
  include_once('head.php');
  $pageName = 'Settings';
?>
  <body class="h-100">
    <div class="container-fluid">
      <div class="row">
      <?php
        include_once('sidebar.php');
      ?>
        <main class="main-content col-lg-10 col-md-9 col-sm-12 p-0 offset-lg-2 offset-md-3">
            <?php
              include_once('navbar.php');
            ?>
          <div class="main-content-container container-fluid px-4">
            <!-- Page Header -->
            <div class="page-header row no-gutters py-4">
              <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
                <span class="text-uppercase page-subtitle">Settings</span>
                <h3 class="page-title">Edit Settings</h3>
              </div>
            </div>
            <!-- End Page Header -->
            
            <div class="row justify-content-center">
              <!-- Available languages Component -->
              <div class="col-12">
              <table class="table bg-white rounded w-100" >
                <thead>
                    <tr>
                    <th scope="col" >#</th>
                    <th scope="col">Setting Name</th>
                    <th scope="col">Setting Value</th>
                    <!-- <th scope="col" class="text-muted">Translated</th> -->
                    <!-- <th scope="col">Default Language</th> -->
                    <th scope="col" colspan="2" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="languageTable">
                  <script>
                    let loc = window.location.pathname;
                    let dir = loc.substring(0, loc.lastIndexOf('/'));

                    (async () => {
                      const res = await fetch(`${dir}/coreApi/settingsApi.php?getAllConfigs`);
                      const settings = await res.json();
                      // console.log(settings);
                      let table = document.getElementById('settingsTable');
                      let id = 0;
                      for (const [key, value] of Object.entries(settings)) {
                        let valueWithoutQuotes = JSON.stringify(value).replaceAll('"', '~');
                        table.insertAdjacentHTML('beforebegin', `
                        <tr>
                          <th scope="row">${++id}</th>
                          <td >${key}</td>
                          <td style="min-width: 50%;max-width: 50%;">
                            <p style="overflow-wrap: anywhere;min-width: 100%;max-width: 100%;">
                            ${(typeof(value) == 'object') ? JSON.stringify(value) : value}
                            </p>
                          </td>
                          <td colspan="2" class="form-inline">
                            <button type="button" class="btn btn-white w-50" data-toggle="modal" data-target="#removeLanguage" data-name="${key}" data-value="${(typeof(value) == 'object') ? JSON.stringify(value) : value}" disabled>
                              <span class="text-danger">
                                <i class="material-icons">clear</i>
                              </span> 
                              Delete 
                            </button>
                            <button type="button" class="btn btn-white w-50" onclick="changeSetting('${key}', '${valueWithoutQuotes}')">
                              <span class="text-warning">
                                <i class="material-icons">edit</i>
                              </span> 
                              Edit 
                            </button>
                          </td>
                        </tr> 
                        `);
                        
                      }
                    })();
                    
                    function setSettings(name, value){
                      let xhr = new XMLHttpRequest();
                      
                      xhr.open('GET', `${dir}/coreApi/settingsApi.php?setConfig&confName=${name}&confValue=${value}`);
                      xhr.send();
                      // console.log(langCode);console.log(langName);
                      location.reload();
                    }
                    function changeSetting(name, value){
                      value = value.replaceAll('~', '"');
                      $('#settingName').val(name).prop( "disabled", true );;
                      $('#settingValue').val(value);
                    }
                  </script>            
                    <tr id="settingsTable">
                    <th scope="row" colspan="2" class="text-center">
                      <span class="text-success">
                        <i class="material-icons">add</i>
                        Change Or Add Setting
                      </span>
                    </th>
                    <td colspan="2" class="form-inline">
                      <input type="text" class="form-control w-25" id="settingName" placeholder="Setting Name"> </div>
                      <input type="text" class="form-control w-75" id="settingValue" placeholder="Setting Value" style="overflow-wrap: anywhere;"> </div>
                                            
                    </td>
                    <td>
                      <button type="button" class="btn btn-white w-100" onclick="setSettings($('#settingName').val(),$('#settingValue').val())">
                        <span class="text-success">
                          <i class="material-icons">add</i>
                        </span> Change Or Add Setting
                      </button>
                    </td>
                </tr>
              </tbody>
              </table>
                
              </div>
              <!-- End Available languages Component -->
            </div>
          </div>
          <?php
            include_once('footer.php');
          ?>