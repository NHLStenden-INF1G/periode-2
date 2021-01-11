
   const parseResults = (obj, liveSearch) => {


    var opleidingResult, docentVideoResult, tagsResult, vakResult, videoResult;
    docentVideoResult = obj.docentVideoResult, tagsResult = obj.tagsResult, vakResult = obj.vakResult, videoResult = obj.videoResult;
    if ('opleidingResult' in obj) {
            opleidingResult = obj.opleidingResult;
    } else {
            opleidingResult = 0;
    }
    liveSearch.innerHTML = '';
    var opleidingResultHTMLContainer = "<div id='opleidingResultHTMLContainer'><div class='searchTitle'>Opleidingen</div></div>", 
        docentVideoResultHTMLContainer = "<div id='docentVideoResultHTMLContainer'><div class='searchTitle'>Docenten</div></div>", 
        tagsResultHTMLContainer = "<div id='tagsResultHTMLContainer'><div class='searchTitle'>Tags</div></div>", 
        vakResultHTMLContainer = "<div id='vakResultHTMLContainer'><div class='searchTitle'>Vakken</div></div>", 
        videoResultHTMLContainer = "<div id='videoResultHTMLContainer'><div class='searchTitle'>Video's</div></div>";
        
        var opleidingResultHTML = [], docentVideoResultHTML = [], tagsResultHTML = [], vakResultHTML = [], videoResultHTML = [];

        if(opleidingResult.length > 0){
            liveSearch.insertAdjacentHTML('beforeend', opleidingResultHTMLContainer),
            opleidingResultHTMLContainer = document.getElementById('opleidingResultHTMLContainer');

            for (const property in opleidingResult) {
                item = '<div class="searchResult"><i class="fa fa-dot-circle-o"></i><a href="/watch/'+ opleidingResult[property].video_id+'"> '+ opleidingResult[property].titel+' | Geupload door ('+ opleidingResult[property].voornaam+' '+ opleidingResult[property].achternaam+') | Vak: '+ opleidingResult[property].vak_naam+'  </a></div>';
                opleidingResultHTML.push(item);
            }

            opleidingResultHTMLContainer.insertAdjacentHTML('beforeend', opleidingResultHTML.join('\n'));;
        }
        
        if(docentVideoResult.length > 0){
            liveSearch.insertAdjacentHTML('beforeend', docentVideoResultHTMLContainer),
            docentVideoResultHTMLContainer = document.getElementById('docentVideoResultHTMLContainer');

            for (const property in docentVideoResult) {
                item = '<div class="searchResult"><i class="fa fa-dot-circle-o"></i><a href="/watch/'+ docentVideoResult[property].video_id+'"> '+ docentVideoResult[property].titel+' | Geupload door ('+ docentVideoResult[property].voornaam+' '+ docentVideoResult[property].achternaam+') | Vak: '+ docentVideoResult[property].vak_naam+'  </a></div>';
                docentVideoResultHTML.push(item);
            }

            docentVideoResultHTMLContainer.insertAdjacentHTML('beforeend', docentVideoResultHTML.join('\n'));;
        }

        if(tagsResult.length > 0){
            liveSearch.insertAdjacentHTML('beforeend', tagsResultHTMLContainer),
            tagsResultHTMLContainer = document.getElementById('tagsResultHTMLContainer');

            for (const property in tagsResult) {
                item = '<div class="searchResultTags"><a href="/zoeken/tags/'+tagsResult[property].tag_id +'" class="videoTag">#'+tagsResult[property].naam +'</a></div>';
                tagsResultHTML.push(item);
            }

            tagsResultHTMLContainer.insertAdjacentHTML('beforeend', tagsResultHTML.join('\n'));
        } 

        if(vakResult.length > 0){
            liveSearch.insertAdjacentHTML('beforeend', vakResultHTMLContainer),
            vakResultHTMLContainer = document.getElementById('vakResultHTMLContainer');

            for (const property in vakResult) {
                item = '<div class="searchResult"><i class="fa fa-dot-circle-o"></i><a href="/watch/'+ vakResult[property].video_id+'"> '+ vakResult[property].titel+' | Geupload door ('+ vakResult[property].voornaam+' '+ vakResult[property].achternaam+') | Vak: '+ vakResult[property].vak_naam+'  </a></div>';
                vakResultHTML.push(item);
            }

            vakResultHTMLContainer.insertAdjacentHTML('beforeend', vakResultHTML.join('\n'));
        } 

        if(videoResult.length > 0){
            liveSearch.insertAdjacentHTML('beforeend', videoResultHTMLContainer),
            videoResultHTMLContainer = document.getElementById('videoResultHTMLContainer');

            for (const property in videoResult) {
                item = '<div class="searchResult"><i class="fa fa-dot-circle-o"></i><a href="/watch/'+ videoResult[property].video_id+'"> '+ videoResult[property].titel+' | Geupload door ('+ videoResult[property].voornaam+' '+ videoResult[property].achternaam+') | Vak: '+ videoResult[property].vak_naam+'  </a></div>';
                videoResultHTML.push(item);
            }

            videoResultHTMLContainer.insertAdjacentHTML('beforeend', videoResultHTML.join('\n'));
        }

        if(!opleidingResult.length && !docentVideoResult.length && !tagsResult.length && !vakResult.length && !videoResult.length) {
            liveSearch.innerHTML = '<div class="searchResult">Geen resultaten</div>';
        }
        const liveInput = document.getElementById('liveInput');

        liveSearch.style.position = "absolute";
        liveSearch.style.top =  liveInput.getBoundingClientRect().top + liveInput.getBoundingClientRect().height+'px';
        liveSearch.style.left = liveInput.getBoundingClientRect().left+'px';
        liveSearch.style.width = liveInput.getBoundingClientRect().width+'px';

        liveSearch.style.borderBottomLeftRadius  = "20px";
        liveSearch.style.borderBottomRightRadius  = "20px";

        liveInput.style.borderBottomLeftRadius = "0px";
        liveInput.style.borderBottomRightRadius = "0px";

} 

function showResult(str) {
    if (str.length==0) {
        document.getElementById("livesearch").innerHTML="";
        document.getElementById("livesearch").style.border="0px";

        document.getElementById("liveInput").style.borderBottomLeftRadius = "20px";
        document.getElementById("liveInput").style.borderBottomRightRadius = "20px";
        return;
    }
    var xmlhttp=new XMLHttpRequest();
                
    xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
        var obj = JSON.parse(this.getResponseHeader("searchResult"));
        const liveSearch = document.getElementById("livesearch");

            parseResults(obj, liveSearch);
        }
    }
    xmlhttp.open("POST", '/zoeken',true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("searchTerm=" +str);
}