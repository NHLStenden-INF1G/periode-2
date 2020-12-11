// shim layer with setTimeout fallback
    window.requestAnimFrame = (function(){
        return  window.requestAnimationFrame       ||
        window.webkitRequestAnimationFrame ||
        window.mozRequestAnimationFrame    ||
        function( callback ){
            window.setTimeout(callback, 1000 / 60);
        };
    })();

    var Anim = { //animation settings
        'duration': 3000,
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
        var stop = {'pos': pos, 'colors':colors, 'currColor': null}
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

            //interpolate both stop 1&2 colors to get new color based on animaiton unit
            r = Math.floor(lerp(startColor.r, endColor.r, Anim.currUnit));
            g = Math.floor(lerp(startColor.g, endColor.g, Anim.currUnit));
            b = Math.floor(lerp(startColor.b, endColor.b, Anim.currUnit));

            stop.currColor = 'rgb('+r+','+g+','+b+')';
        }

        // update current stop and animation units if interpolaiton is complete
        if (Anim.currUnit >= 1.0){
            Anim.currUnit = 0;
            if(this.currentStop < stopsLength){
                    this.currentStop++;
                } else {
                    this.currentStop = 0;
                }
            }
        Anim.currUnit += step_u; //increment animation unit
    }

    Gradient.prototype.draw = function(){
        var gradient = ctx.createLinearGradient(0, 0, this.width,this.height);

        for(var i = 0; i < this.colorStops.length; i++){
            var stop = this.colorStops[i],
            pos = stop.pos,
            color = stop.currColor;

            gradient.addColorStop(pos,color);
        }

        this.ctx.clearRect(0,0,this.width,this.height);
        this.ctx.fillStyle = gradient;
        this.ctx.fillRect(0,0,this.width,this.height);
        
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

    var $width, $height, gradient,
        canvas = document.getElementById("canvas"),
        ctx = canvas.getContext("2d"),
        circles = [],
        stopAColor = [
            { 'r':'92', 'g':'37', 'b':'141' },
            { 'r':'131', 'g':'77', 'b':'155' },
            { 'r':'255', 'g':'0', 'b':'204' },
            { 'r':'0', 'g':'0', 'b':'0' }
        ]
        stopBColor = [
            { 'r':'67', 'g':'137', 'b':'162' },
            { 'r':'208', 'g':'78', 'b':'214' },
            { 'r':'51', 'g':'51', 'b':'153' },
            { 'r':'0', 'g':'0', 'b':'0' }
        ];

    var updateUI = function(){
        var W = window.innerWidth, H = window.innerHeight;
        canvas.width = W;
        canvas.height = H;

        gradient = new Gradient(ctx, canvas.width, canvas.height, circles);
        gradient.addStop(0, stopAColor);
        gradient.addStop(1, stopBColor);
        
        if(circles.length < 20){
            for(var i = 0; i < 20; i++ ){
                gradient.circles.push(new gradient.addCircles(canvas.width, canvas.height));
            }
        }
    }   

    //interpolation
    function lerp(a, b, u) {
        return (1 - u) * a + u * b;
    }