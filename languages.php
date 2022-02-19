<?php
  include_once('head.php');
  $pageName = 'Languages';
?>
  <body class="h-100">
    <?php
      include_once('settingsButton.php');
    ?>
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
                <h3 class="page-title">Available Languages</h3>
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
                    <th scope="col">Language Code</th>
                    <th scope="col">Language Name</th>
                    <th scope="col" class="text-muted">Translated</th>
                    <th scope="col">Default Language</th>                    
                    <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody id="languageTable">
                  <script>
                    let loc = window.location.pathname;
                    let dir = loc.substring(0, loc.lastIndexOf('/'));
                    
                    let xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function() {
                      // Verify that the request is done and completed successfully
                      if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                          let json = xhr.responseText;
                          // console.log(json['hy']);
                          let data = JSON.parse(json); // Parse the JSON into an actual object/array
                          printAvailableLanguages(data);
                          // console.log(data);
                        } else {
                          console.log("Can't load available languages.");
                        }
                      }
                    };
                    xhr.open('GET', dir+'/coreApi/languageApi.php?getAvailableLanguages');
                    xhr.send();

                    function printAvailableLanguages(data){
                      // console.log(typeof data);
                      (async () => {
                        const res = await fetch(`${dir}/coreApi/languageApi.php?getDefaultLanguage`);
                        const defaultLanguage = await res.text();
                      
                        let table = document.getElementById('addLanguage');
                        let id = 0;
                        for (const [key, value] of Object.entries(data)) {
                          // console.log(`${key}: ${value}`);
                          let checked = (key == defaultLanguage) ? 'checked' : '';
                          table.insertAdjacentHTML('beforebegin', `
                          <tr>
                            <th scope="row">${++id}</th>
                            <td>${key}</td>
                            <td>${value}</td>
                            <td class="text-muted">%</td>
                            <td>  
                              <input class="form-check-input" type="radio" name="flexRadioDefault" id="selectDefaultLanguage_${key}" onclick="setDefaultLanguage('${key}');" ${checked}>
                              <label class="form-check-label" for="selectDefaultLanguage_${key}">
                                Select as default language.
                              </label>

                            </td>
                            <td>

                              <button type="button" class="btn btn-white w-100" data-toggle="modal" data-target="#removeLanguage" data-langcode="${key}">
                                <span class="text-danger">
                                  <i class="material-icons">clear</i>
                                </span> 
                                Delete 
                              </button>
                            </td>
                          </tr> 
                          `);
                          
                        }
                      })();
                      
                    }
                  </script>
                  <?php
                    // $lang = new Language;
                    // $availableLanguages = $lang->getAvailableLanguages();
                    // $count = 1;
                    // foreach ($availableLanguages as $key => $value) {
                    //   echo "
                    //   <tr>
                    //     <th scope='row'>$count</th>
                    //       <td>$key</td>
                    //       <td>$value</td>
                    //       <td>45%</td>
                    //       <td>
                    //         <button type='button' class='btn btn-white w-100'>
                    //           <span class='text-danger'>
                    //             <i class='material-icons'>clear</i>
                    //           </span> 
                    //           Delete 
                    //         </button>
                    //     </td>
                    //   </tr>
                    //   ";
                    //   ++$count;
                    // }
                  ?>
                    <!-- <tr>
                        <th scope="row">1</th>
                        <td>ru</td>
                        <td>Russian</td>
                        <td>45%</td>
                        <td>
                          <button type="button" class="btn btn-white w-100">
                            <span class="text-danger">
                              <i class="material-icons">clear</i>
                            </span> 
                            Delete 
                          </button>
                        </td>
                    </tr> -->
                    

                    <div class="modal fade" id="removeLanguage" tabindex="-1" role="dialog" aria-labelledby="removeLanguageLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="removeLanguageLabel">Delete Available Language</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <!-- <form>
                              <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Recipient:</label>
                                <input type="text" class="form-control" id="recipient-name">
                              </div>
                              <div class="form-group">
                                <label for="message-text" class="col-form-label">Message:</label>
                                <textarea class="form-control" id="message-text"></textarea>
                              </div>
                            </form> -->
                            <span class="text-danger" id="modalBody">
                                Are you sure?
                            </span>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" id="removeLanguageButton">Remove Language</button>
                          </div>
                        </div>
                      </div>
                    </div>       
                   <script>
                    document.addEventListener('DOMContentLoaded', function() {

                      $('#removeLanguage').on('show.bs.modal', function (event) {
                      let button = $(event.relatedTarget) // Button that triggered the modal
                      let langCode = button.data('langcode') // Extract info from data-* attributes
                      // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                      // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
                      let modal = $(this)
                      // modal.find('#modalBody').text(' ' + langCode)
                      // modal.find('.modal-body input').val(langCode)
                      $("#removeLanguageButton").on("click", function(){ removeAvailableLanguage(langCode); });
                    });
                  } , false);
                    function removeAvailableLanguage(langCode){
                      let xhr = new XMLHttpRequest();
                      
                      xhr.open('GET', dir+'/coreApi/languageApi.php?removeLanguage&langCode=' + langCode);
                      xhr.send();
                      location.reload();
                    }

                    function setDefaultLanguage(langCode){
                      let xhr = new XMLHttpRequest();
                      
                      xhr.open('GET', dir+'/coreApi/languageApi.php?setDefaultLanguage&langCode=' + langCode);
                      xhr.send();
                      location.reload();
                    }
                    

                    
                   </script> 
                    
                    <tr id="addLanguage">
                        <th scope="row" colspan="2" class="text-center">
                          <span class="text-success">
                            <i class="material-icons">add</i>
                            Add new language
                          </span>
                        </th>
                        <td>
                          <input type="text" class="form-control" id="langCode" placeholder="Language Code ex. hy"> </div>
                        </td>
                        <td>
                          <input type="text" class="form-control" id="langName" placeholder="Language Name ex. Հայերեն"> </div>
                        </td>
                        <td>
                          <button type="button" class="btn btn-white w-100" onclick="addLanguage($('#langCode').val(),$('#langName').val())">
                            <span class="text-success">
                              <i class="material-icons">add</i>
                            </span> Add Language 
                          </button>
                        </td>
                    </tr>
                </tbody>
                </table>
                <script>
                  function addLanguage(langCode, langName){
                    let xhr = new XMLHttpRequest();
                    
                    xhr.open('GET', `${dir}/coreApi/languageApi.php?addLanguage&langCode=${langCode}&langName=${langName}`);
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
          </div>
          <?php
            include_once('footer.php');
          ?>