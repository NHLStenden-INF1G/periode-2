    window.requestAnimFrame = (function(){
        return  window.requestAnimationFrame       ||
        window.webkitRequestAnimationFrame ||
        window.mozRequestAnimationFrame    ||
        function( callback ){
            window.setTimeout(callback, 1000 / 60);
        };
    })();

    let loaded = false;


    const med = document.querySelector("video");

    var Anim = { //animation settings
        'duration': 10000, 
        'interval' : 10,
        'stepUnit' : 1.0,
        'currUnit' : 0.0
    }

    function Gradient(context, width, height, circles){
        this.ctx = context;
        this.width = width;
        this.height = height;
        this.circles = circles;
        this.colorStops = [];
        this.currentStop = 0;
    }

    Gradient.prototype.addCircles = function(W, H){
        
        this.x = Math.random()*W;
        this.y = Math.random()*H;

        this.vx = 0.2+Math.random()*1;
        this.vy = -this.vx;

        this.r = 10 + Math.random()*50;
    }

    Gradient.prototype.addStop = function(pos, colors){
        var stop = {'pos': pos, 'colors':colors, 'currColor':null}
        this.colorStops.push(stop)
    }

    Gradient.prototype.addStopVid = function(pos, colors, currColor){
        var stop = {'pos': pos, 'colors':colors, 'currColor':currColor}
        this.colorStops.push(stop)
    }

    Gradient.prototype.updateStops = function(){ //interpolate colors of stops
        var steps = Anim.duration / Anim.interval,
        step_u = Anim.stepUnit/steps
        stopsLength = this.colorStops[0].colors.length - 1;

        for(var i = 0; i < this.colorStops.length; i++){ //cycle through all stops in gradient
            var stop = this.colorStops[i],
            startColor = stop.colors[this.currentStop],//get stop 1 color
            endColor, r, g, b;

            if(this.currentStop < stopsLength){ //get stop 2 color, go to first if at last stop
                endColor = stop.colors[this.currentStop + 1];
            } else {
                endColor = stop.colors[0];
            }

            //interpolate both stop 1&2 colors to get new color based on animation unit

            r = Math.floor(lerp(startColor.r, endColor.r, Anim.currUnit));
            g = Math.floor(lerp(startColor.g, endColor.g, Anim.currUnit));
            b = Math.floor(lerp(startColor.b, endColor.b, Anim.currUnit));

            stop.currColor = 'rgb('+r+','+g+','+b+')';
        }

        // update current stop and animation units if interpolation is complete
        if (Anim.currUnit >= 1.0){ 
            Anim.currUnit = 0;
            if(this.currentStop < stopsLength){
                    this.currentStop++;
                } else {
                    this.currentStop = 0;
                }
            if(updateVideoGradient(6)) {
                stopsLength = this.colorStops[0].colors.length - 1;
                this.currentStop = 0;
            }
        }
        Anim.currUnit += step_u; //increment animation unit
    }

    let startedPlay = false;

    Gradient.prototype.draw = function(){
        
        
       var x1 = 0, y1 = 0, x2, y2, length = this.width;

        // calculate gradient line based on angle
        x2 = x1 + Math.cos(130* Math.PI) * (length * 1.4);
        y2 = y1 + Math.sin(130* Math.PI) * (length * 1.4);
        
      var gradient = ctx.createLinearGradient(0, this.height, x2, y2);


        for(var i = 0; i < this.colorStops.length; i++){
            var stop = this.colorStops[i],
            pos = stop.pos,
            color = stop.currColor;
            gradient.addColorStop(pos,color);
        }

        this.ctx.clearRect(0,0,this.width,this.height);
        this.ctx.fillStyle = gradient;
        this.ctx.fillRect(0,0,this.width,this.height);
        if(!startedPlay || med.ended) {
            for(var j = 0; j < this.circles.length; j++) {
                var c = this.circles[j];
                ctx.beginPath();
                ctx.globalCompositeOperation = "lighter";		
                ctx.arc(c.x, c.y, c.r, Math.PI*2, false);
                ctx.fill();
    
                c.x += c.vx;
                c.y += c.vy;
    
    
                if(c.x < -50) c.x = this.width+50;
                if(c.y < -50) c.y = this.height+50;
                if(c.x > this.width+50) c.x = -50;
                if(c.y > this.height+50) c.y = -50;
            }  
        }
        else {
            document.getElementById("canvas").style.filter = "saturate(3)"; // verwijdert ge deze nie wanneer die stopt?
        }

    }

    var $width, $height, gradient,
        canvas = document.getElementById("canvas"),
        ctx = canvas.getContext("2d"),
        circles = [],
        stopAColor = [
            { 'r':'0', 'g':'90', 'b':'167' },
            { 'r':'131', 'g':'58', 'b':'180' },
            { 'r':'255', 'g':'0', 'b':'204' },
            { 'r':'0', 'g':'132', 'b':'135' }
        ]
        stopBColor = [
            { 'r':'0', 'g':'90', 'b':'167' },
            { 'r':'253', 'g':'29', 'b':'29' },
            { 'r':'255', 'g':'0', 'b':'204' },
           { 'r':'51', 'g':'51', 'b':'153' }
        ],
        stopCColor = [
            { 'r':'0', 'g':'132', 'b':'135' },
           { 'r':'253', 'g':'29', 'b':'29' },
           { 'r':'81', 'g':'81', 'b':'153' },
           { 'r':'1', 'g':'33', 'b':'135' }
        ];

    var resizeCanvas = function(){
        var W = window.innerWidth, H = window.innerHeight;

        var element = document.querySelector('#canvas');

        canvas.height= window.getComputedStyle(element).height.split("px")[0];
        canvas.width = W;
        gradient.height = canvas.height;
        gradient.width = canvas.width;
    }

    var updateUI = function(){
        var W = window.innerWidth, H = window.innerHeight;

        var element = document.querySelector('#canvas');

        canvas.height= window.getComputedStyle(element).height.split("px")[0];
        canvas.width = W;
        gradient = new Gradient(ctx, canvas.width, canvas.height, circles);
        gradient.colorStops = []
        if(!loaded) {
            gradient.addStop(0, stopAColor);
            gradient.addStop(0.5, stopBColor);
            gradient.addStop(1, stopCColor);
        }

        if(circles.length < 20){
            for(var i = 0; i < 20; i++ ){
                gradient.circles.push(new gradient.addCircles(canvas.width, canvas.height));
            }
        }
        canvas.addEventListener('click', (e) => {
            let circleClicked = false;
            for(let i = 0; i < this.circles.length; i++) {
                c = this.circles[i];
                if(Math.sqrt(Math.pow(c.x-e.clientX, 2) + Math.pow(c.y-e.clientY, 2)) < c.r) {
                    console.log('Circle clicked');
                    if(Anim.duration === 10000) {
                        Anim.duration = 100;
                    } else {
                        Anim.duration = 10000;
                    }
                    break;
                }
            }
            if(!circleClicked) {
                console.log('Canvas clicked');
            }
        });
    }   

    if(med){
        med.onplay = function() {
            Anim.currUnit = 1.0;
            startedPlay = true;
        }
        med.onended = function() {
        stopAColor = [
            { 'r':'0', 'g':'90', 'b':'167' },
            { 'r':'131', 'g':'58', 'b':'180' },
            { 'r':'255', 'g':'0', 'b':'204' },
            { 'r':'0', 'g':'132', 'b':'135' }
        ];
        stopBColor = [
            { 'r':'0', 'g':'90', 'b':'167' },
            { 'r':'253', 'g':'29', 'b':'29' },
            { 'r':'255', 'g':'0', 'b':'204' },
           { 'r':'51', 'g':'51', 'b':'153' }
        ];
        stopCColor = [
            { 'r':'0', 'g':'132', 'b':'135' },
           { 'r':'253', 'g':'29', 'b':'29' },
           { 'r':'81', 'g':'81', 'b':'153' },
           { 'r':'1', 'g':'33', 'b':'135' }
        ];
        var curA = 'rgb('+ stopAColor[0].r + ',' + stopAColor[0].g + ',' + stopAColor[0].b + ')';
        var curB = 'rgb('+ stopBColor[0].r + ',' + stopBColor[0].g + ',' + stopBColor[0].b + ')';
        var curC = 'rgb('+ stopCColor[0].r + ',' + stopCColor[0].g + ',' + stopCColor[0].b + ')';
        gradient.colorStops = [];
        gradient.addStop(0, stopAColor, curA);
        gradient.addStop(0.5, stopBColor, curB);
        gradient.addStop(1, stopCColor, curC);
        Anim.duration = 10000;
        document.getElementById("canvas").style.filter = "";

        }
    }
    let colorHistory = [];
    let colorHistory2 = [];
    let colorHistory3 = [];
    let colorHistory4 = [];
    let colorHistory5 = [];


    const updateVideoGradient = function (/*number*/divisionCount) {
        const videoCanvas = document.querySelector(".videoCanvas");
        const videoCTX = videoCanvas.getContext("2d");
    
        if (med) {
            med.addEventListener("loadedmetadata", function (e) {
                loaded = true;
            }, false);
        }
    
        if (med && loaded) {
            if (!med.paused && !med.ended) {
                videoCTX.drawImage(med, 0, 0, med.videoWidth / 4, med.videoHeight / 4);
                gradient.colorStops = [];
                colorHistory.length = divisionCount;
                for(let i = 0; i < colorHistory.length; i++) {
                    if(colorHistory[i] === undefined) {
                        colorHistory[i] = [];
                    }
                }
                for (let i = 0; i < divisionCount; i++) {
                    const frame_data = videoCTX.getImageData(med.videoWidth / 4 * i / divisionCount, 0, med.videoWidth / 4 * (i + 1) / divisionCount, med.videoHeight / 4).data;
                    const frame_data_length = (frame_data.length / 4) / 4;
                    let pixel_count = 0;
                    let rgb_sums = [0, 0, 0];
    
                    for (let j = 0; j < frame_data_length; j += 8) {
                        rgb_sums[0] += frame_data[j * 8];
                        rgb_sums[1] += frame_data[j * 8 + 1];
                        rgb_sums[2] += frame_data[j * 8 + 2];
    
                        pixel_count++;
                    }
    
                    rgb_sums[0] = Math.floor(rgb_sums[0] / pixel_count);
                    rgb_sums[1] = Math.floor(rgb_sums[1] / pixel_count);
                    rgb_sums[2] = Math.floor(rgb_sums[2] / pixel_count);
    
                    if (colorHistory[i].length > 1) {
                        colorHistory[i].splice(0, 1);
                    }
                    /*
                    if(i === divisionCount - 1) {
                        rgb_sums[0] += 60;
                        rgb_sums[1] += 60;
                        rgb_sums[2] += 60;
                    }
                    */
    
                    colorHistory[i].push({
                        'r': rgb_sums[0].toString(),
                        'g': rgb_sums[1].toString(),
                        'b': rgb_sums[2].toString()
                    })
    
                    const stopColor = colorHistory[i];
    
                    Anim.duration = getRandomInt(170, 300);
                    const cur = 'rgb(' + stopColor[0].r + ',' + stopColor[0].g + ',' + stopColor[0].b + ')';
                    gradient.addStopVid(i/(divisionCount-1), stopColor, cur);
                }
                return true;
            } else {
                Anim.duration = 10000;
            }
        }
        return false;
    };
    

    //interpolation
    function lerp(a, b, u) {
        return (1 - u) * a + u * b;
    }

function getRandomInt(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min) + min); //The maximum is exclusive and the minimum is inclusive
    }
