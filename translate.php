<?php
  include_once('head.php');
  $pageName = 'Translate';
?>
<body class="h-100" style="overflow:hidden;">
    <style>
        #translationsTable th{
            min-width:300px;
        }
        #translationsTable th.id, td.id{
            left:0px;
            min-width:50px !important;
            position: absolute;
            background-color:white;
        }
        #translationsTable th.key, td.key{
            left:50px;
            min-width:200px !important;
            position: absolute;
            background-color:white;
            border-right: 1px solid silver !important;
        }
    </style>
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
                        <!-- <span class="text-uppercase page-subtitle"><?//=$pageName?></span> -->
                        <h3 class="page-title"><?=$pageName?></h3>
                    </div>
                </div>
                
                <div class="row" id="translatedFiles">
                    <script>
                    let loc = window.location.pathname;
                    let dir = loc.substring(0, loc.lastIndexOf('/'));
                      // console.log(typeof data);
                      (async () => {
                        const data = await fetch(`${dir}/coreApi/translateApi.php?getTranslatedFiles`);
                        const translatedFiles = await data.json();
                        // console.log(translatedFiles);

                        let table = document.getElementById('translatedFiles');
                        console.log(translatedFiles);
                        let id = 0;
                        translatedFiles.forEach(value => {
                          table.insertAdjacentHTML('afterbegin', `
                            <div class="col-lg-2 col-6 mb-2">
                                <button class="btn btn-outline-primary w-100" onclick="getTranslations('${value}')">${value}</button>
                            </div>
                          `);
                          
                        });
                      })();

                    function getTranslations(fileName){
                        document.getElementById('fileName').innerHTML = fileName;
                        (async () => {
                        const data = await fetch(`${dir}/coreApi/translateApi.php?getTranslationFileAllLanguages&fileName=${fileName}`);
                        const translations = await data.json();

                        let thead = document.getElementById('availableLanguages');
                        thead.innerHTML = `
                            <th scope="col" class="border-0 id" >#</th>
                            <th scope="col" class="border-0 key">Key</th>
                            <th scope="col" class="border-0 scrolable"></th>
                        `;
                        let tbody = document.getElementById('tbody');

                        // console.log(translations);
                        // console.log(Object.keys(translations).length)
                        console.log(translations[Object.keys(translations)[0]]);
                        let availableLang = [];
                        for (const [key, value] of Object.entries(translations)) {                           
                            thead.innerHTML += `
                            <th scope="col" class="border-0 scrolable">${key}</th>
                            `;

                            // tbody.innerHTML += `
                            // <tr id="${key}"></tr>
                            // `;
                            availableLang.push(key);
                        }
                        // console.log(availableLang);
                        // console.log(translations['hy']['fsffsfs']);
                        let count = 0;
                        tbody.innerHTML = '';
                        for (const [key, value] of Object.entries(translations[availableLang[0]])){
                            if(key == 'lastUpdate') break;
                            tbody.innerHTML += `
                                <tr id="row_${count}"></tr>
                            `;
                            let col = document.getElementById(`row_${count}`);
                            col.innerHTML = `
                                <td class="id">${count++}</td>
                                <td class="key">${key}</td>
                                <td class="firstScrolable"></td>

                            `;
                            
                            availableLang.forEach(lang => {
                                // let col = document.getElementById(lang);
                               
                                let val = translations[lang][key];
                                col.innerHTML +=  `
                                    <td onclick="updateValue(this,'${fileName}','${lang}','${key}','${(val != '') ? val.replaceAll(/'/g,"\\'") : key.replace(/'/g, "\\'")}')">${(val != '') ? val : key}</td>
                                `;
                            });
                        }                            
                        
                        
                            
                    })();
                    }
                    function updateValue(elem, fileName,  lang, key, val){
                        // console.log(elem);
                        console.log(elem.outerHTML);
                        elem.outerHTML = `
                            <td>
                            <input type="text" value="${val}" onfocusout="updateTranslationByKey(this, '${fileName}', '${lang}', '${key}', this.value)" autofocus>
                            </td>
                        `;
                    }

                    function updateTranslationByKey(elem, fileName, lang, key, val){
                        console.log(fileName);
                        console.log(lang);
                        console.log(key);
                        console.log(val);
                        (async () => {
                            // coreApi/translateApi.php?updateTranslationByKey&fileName=../test.php&langCode=hy&key=a1&value=testtest
                            const data = await fetch(`${dir}/coreApi/translateApi.php?updateTranslationByKey&fileName=${fileName}&langCode=${lang}&key=${key}&value=${val}`);
                            const response = await data.text();
                            console.log(response);
                            elem.parentElement.outerHTML = `
                            <td onclick="updateValue(this,'${fileName}','${lang}','${key}','${(val != '') ? val.replace(/'/g, "\\'") : key.replace(/'/g, "\\'")}')">${(val != '') ? val : key }</td>
                        `;
                        })();
                        // console.log(elem.parentElement.outerHTML);
                        
                    }
                    </script>
                    
                </div>

                <div class="row">
                    <div class="col">
                        <div class="card card-small mb-4" >
                        <div class="card-header border-bottom">
                            <h6 class="m-0" id="fileName"></h6>
                        </div>
                        <div class="card-body p-0 pb-3 text-center" style="overflow-x:auto;">
                            <table class="table mb-0" id="translationsTable">
                            <thead class="bg-light" id="thead">
                                <tr id="availableLanguages">
                                    
                                <!--<th scope="col" class="border-0 id" >#</th>
                                <th scope="col" class="border-0 key">Key</th>
                                <th scope="col" class="border-0 scrolable">Lang1</th>
                                <th scope="col" class="border-0 scrolable">Lang2</th>
                                -->
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                <!-- <tr>
                                <td class="id">1</td>
                                <td class="key">Ali</td>
                                <td class="firstScrolable">Kerry</td>
                                <td>Russian Federation</td>
                                <td>Gdańsk</td>
                                <td>107-0339</td>
                                </tr>
                                -->
                            </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
            </div>  
            
    <?php
    include_once('footer.php');
    ?>