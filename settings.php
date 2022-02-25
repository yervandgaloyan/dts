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
                <h3 class="page-title">Translated Files</h3>
              </div>
            </div>
            <!-- End Page Header -->
            
            <div class="row justify-content-center">
              <!-- Available languages Component -->
              <div class="col-12">
              <table class="table bg-white rounded">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">File Name</th>
                    <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody id="translatedFiles">
                  <script>
                    let loc = window.location.pathname;
                    let dir = loc.substring(0, loc.lastIndexOf('/'));
                      // console.log(typeof data);
                      (async () => {
                        const data = await fetch(`${dir}/coreApi/translateApi.php?getTranslatedFiles`);
                        const translatedFiles = await data.json();
                        // console.log(translatedFiles);

                        let table = document.getElementById('addTranslatedFile');
                        let id = 0;
                        translatedFiles.forEach(value => {
                          table.insertAdjacentHTML('beforebegin', `
                          <tr>
                            <th scope="row">${++id}</th>
                            <td>${value}</td>
                            <td>

                              <button type="button" class="btn btn-white w-100" data-toggle="modal" data-target="#removeTranslatedFile" data-filename="${value}">
                                <span class="text-danger">
                                  <i class="material-icons">clear</i>
                                </span> 
                                Delete 
                              </button>
                            </td>
                          </tr> 
                          `);
                          
                        });
                      })();
                      
                  </script>
                  

                    <div class="modal fade" id="removeTranslatedFile" tabindex="-1" role="dialog" aria-labelledby="removeTranslatedFileLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="removeTranslatedFileLabel">Delete Translated File</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <span class="text-danger" id="modalBody">
                                Are you sure?
                            </span>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" id="removeTranslatedFileButton">Remove File</button>
                          </div>
                        </div>
                      </div>
                    </div>       
                   <script>
                    document.addEventListener('DOMContentLoaded', function() {

                      $('#removeTranslatedFile').on('show.bs.modal', function (event) {
                      let button = $(event.relatedTarget) // Button that triggered the modal
                      let fileName = button.data('filename') // Extract info from data-* attributes
                      // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                      // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
                      let modal = $(this)
                      // modal.find('#modalBody').text(' ' + langCode)
                      // modal.find('.modal-body input').val(langCode)
                      $("#removeTranslatedFileButton").on("click", function(){ removeTranslatedFile(fileName); });
                    });
                  } , false);
                    function removeTranslatedFile(fileName){
                      let xhr = new XMLHttpRequest();
                      
                      xhr.open('GET', dir+'/coreApi/translateApi.php?removeTranslatedFile&fileName=' + fileName);
                      xhr.send();
                      location.reload();
                    }

                    

                    
                   </script> 
                    
                    <tr id="addTranslatedFile">
                        <th scope="row" class="text-center">
                          <span class="text-success">
                            <i class="material-icons">add</i>
                            Add new language
                          </span>
                        </th>
                        <td>
                          <input type="text" class="form-control" id="fileName" placeholder="File name"> </div>
                        </td>
                        <!-- <td>
                          <input type="text" class="form-control" id="langName" placeholder="Language Name ex. Հայերեն"> </div>
                        </td> -->
                        <td>
                          <button type="button" class="btn btn-white w-100" onclick="addTranslatedFile($('#fileName').val())">
                            <span class="text-success">
                              <i class="material-icons">add</i>
                            </span> Add Translated file 
                          </button>
                        </td>
                    </tr>
                </tbody>
                </table>
                <script>
                  function addTranslatedFile(fileName){
                    let xhr = new XMLHttpRequest();
                    
                    xhr.open('GET', `${dir}/coreApi/translateApi.php?addTranslatedFile&fileName=${fileName}`);
                    xhr.send();
                    // console.log(langCode);console.log(langName);
                    location.reload();
                  }
                </script>
                <!-- <div class="card card-small">
                  <div class="card-header border-bottom">
                    <span class="p-1 border-right">Available languages</span>
                    <span class="p-1 border-right">Translated</span>
                  </div>
                  <div class="card-body p-0">
                    <ul class="list-group list-group-small list-group-flush">
                      <li class="list-group-item d-flex px-3">
                        <span class="text-semibold text-fiord-blue">GitHub</span>
                        <span class="ml-auto text-left text-semibold text-reagent-gray">19%</span>
                      </li>
                      
                    </ul>
                  </div>
                  <div class="card-footer border-top">
                    <div class="row">
                      <div class="col">
                        <select class="custom-select custom-select-sm">
                          <option selected>Last Week</option>
                          <option value="1">Today</option>
                          <option value="2">Last Month</option>
                          <option value="3">Last Year</option>
                        </select>
                      </div>
                      <div class="col text-right view-report">
                        <a href="#">Full report &rarr;</a>
                      </div>
                    </div>
                  </div>
                </div> -->
              </div>
              <!-- End Available languages Component -->
            </div>
            <!-- Page Header -->
            <div class="page-header row no-gutters py-4">
              <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
                <span class="text-uppercase page-subtitle">Settings</span>
                <h3 class="page-title">Edit Configs</h3>
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