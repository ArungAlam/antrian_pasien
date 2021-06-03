/*          Copyright Paul Hanlon (paulhan@eircom.net)
            Licence MIT, BSD, same as jQuery;
Please leave the above copyright notice with this code,
if for no other reason than people wanting to get in touch for help/support.
Not much here in the way of documentation. I'm hoping the demo on the website at
http://www.hanpau.com/jquery/unobtrusivetreetable.php will explain most of it,
particularly how the map is constructed.
The options are mostly the images you need to render the tree properly, with the
last five being:-
collapse:
    Either false or an array of parents to collapse. Just put in the highest
    level nodes, this will recurse down through the children and collapse them,
    without them having to be specified as well. Even if you only set one parent
    to be collapsed, it must be in [] brackets.
column:
    A zero based number of the column you want to render the tree in.
stripe:
    Set to true if you want to have striping on alternate rows. False returns a
    plain treetable. An "even" class needs to be set in CSS for background-color.
highlight:
    Set to true if you want to highlight the row that is being hovered over.
    An "over" class needs to be set in CSS for background-color.
onselect:
    Callback function where you can set what you want to happen when a td is selected
    Forces highlighting on td even if there are other elements within. Links and events
    still work though.
*/
(function(jq){
  opts = {openImg: "./library/script/jquery/jqtreetable/tv-collapsable.gif", shutImg: "./library/script/jquery/jqtreetable/tv-expandable.gif", leafImg: "./library/script/jquery/jqtreetable/tv-item.gif", lastOpenImg: "./library/script/jquery/jqtreetable/tv-collapsable-last.gif", lastShutImg: "./library/script/jquery/jqtreetable/tv-expandable-last.gif", lastLeafImg: "./library/script/jquery/jqtreetable/tv-item-last.gif", vertLineImg: "./library/script/jquery/jqtreetable/vertline.gif", blankImg: "./library/script/jquery/jqtreetable/blank.gif", collapse: false, column: 0, striped: false, highlight: false, onselect: false};

  jq.fn.jqTreeTable = function(map, options){
     var mapa = [], mapb = [], num=0,obj=this;
     
    for (var i in opts){//Sort out the options
      opts[i] = (options[i])? options[i]: opts[i];
    }
    for (var x=0,xl=map.length; x<xl;x++){//From map of parents, get map of kids
      num = map[x];
      if (!mapa[num]){
        mapa[num] = [];
      }
      mapa[num].push(x+1);
    }
    buildText(obj,mapa,mapb,0, "");
    jq("tr", this).each(function(i){//Inject the images into the column to make it work
      jq(this).children("td").eq(opts.column).prepend(mapb[i]);
    });
    if (opts.collapse instanceof Array){//Set nodes to collapsed
      for (var y=0, yl=opts.collapse.length; y<yl; y++){
        collapseKids(obj,mapa,mapb,opts.collapse[y]);
      }
    }
    if(opts.striped){jq("tr:even", obj).addClass("even")};//Stripe all even rows
    jq(".parimg", this).each(function(i){
      var $this = jq(this);
      $this.click(function(){
          var mulai = eval(jq(obj).eq(0).attr("id").length+1);
        var num = $this.attr("id").substr(mulai);//Number of the row
        if ($this.parents("tr").next().is(".collapsed")){//Then expand immediate children
          expandKids(obj,mapa,mapb,num);
        }else{//Collapse all and set image to opts.shutImg or opts.lastShutImg on parents
          collapseKids(obj,mapa,mapb,num);
        }
        if(opts.striped){stripe(obj,num-1)};//Restripe all the rows under this one
      });
    });
    if (opts.highlight){//This is where it highlights the rows
      jq("tr", this).hover(function(){
        jq(this).addClass("over");
      },
      function(){
        jq(this).removeClass("over");
      });
    }
    jq(obj).click( function( event ) {//This looks after selecting td's
      var $target = (event.target.tagName == "TD")?jq(event.target):jq(event.target).parents("td");
      
      if (typeof opts.onselect == "function"){
        jq("td.selected", this).removeClass("selected");
        $target.addClass("selected");
        opts.onselect($target);
      };
    });
  };
  buildText = function(obj,mapa,mapb,parno, preStr){//Recursively build up the text for the images that make it work
    var mp = mapa[parno], ro=0, pre="", pref, img;
    for (var y=0, yl=mp.length;y<yl;y++){
      ro = mp[y];
      if (mapa[ro]){//It's a parent as well. Build it's string and move on to it's children
        if (y==yl-1){//It's the last child, It's child will have a blank field behind it
          pre = opts.blankImg;
          img = opts.lastOpenImg;
        }else{
          pre = opts.vertLineImg;
          img = opts.openImg;
        }
        mapb[ro-1] = preStr + '<img src="'+img+'" class="parimg" id="r'+jq(obj).eq(0).attr("id")+ro+'">';
        pref = preStr + '<img src="'+pre+'" class="preimg">';
        buildText(obj,mapa,mapb,ro, pref);
      }else{//it's a child
        img = (y==yl-1)? opts.lastLeafImg: opts.leafImg;//It's the last child, It's child will have a blank field behind it
        mapb[ro-1] = preStr + '<img src="'+img+'" class="ttimage">';
      }
    }
  };
  expandKids = function(obj,mapa,mapb,num){//Expands immediate children
    jq("#r"+jq(obj).eq(0).attr("id")+num).attr("src", function(){
      return (this.src.substr(this.src.lastIndexOf("/")+1)==opts.lastShutImg)? opts.lastOpenImg: opts.openImg;
    });
    for (var x=0, xl=mapa[num].length;x<xl;x++){
      jq("tr",obj).eq(mapa[num][x]-1).removeClass("collapsed");
    }
  };
  collapseKids = function(obj,mapa,mapb,num){//Recursively collapses all children and their children and changes
    var mapnumx=0;             //parent images to collapsed state
    jq("#r"+jq(obj).eq(0).attr("id")+num).attr("src", function(){
      if (this.src.substr(this.src.lastIndexOf("/")+1)!=opts.lastShutImg){
        return (this.src.substr(this.src.lastIndexOf("/")+1)==opts.lastOpenImg)? opts.lastShutImg: opts.shutImg;
      }
    });
    for (var x=0, xl=mapa[num].length;x<xl;x++){
      mapnumx = mapa[num][x];
      jq("tr",obj).eq(mapnumx-1).addClass("collapsed");
      if (mapa[mapnumx]){
        collapseKids(obj,mapa,mapb,mapnumx);
      }
    }
  };
  stripe = function(obj,num){//Dynamically stripes rows after expanding/collapsing
    var isStriped = (jq("tr",obj).eq(num).attr("class")=="even over")? 0:1;
    jq("tr",obj).gt(num).not(".collapsed").each(function(i){
      if ((i+isStriped) % 2==0){
        jq(this).removeClass("even");
      }else{
        jq(this).addClass("even");
      }
    });
  };
  return this;
})(jQuery);
