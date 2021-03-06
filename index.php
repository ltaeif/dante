﻿<script type="text/javascript" src="scripts/jquery.js"></script>

<script type="text/javascript" src="scripts/redist/when.js"></script>
<script type="text/javascript" src="scripts/core.js"></script>
<script type="text/javascript" src="scripts/graphics.js"></script>
<script type="text/javascript" src="scripts/mapimage.js"></script>
<script type="text/javascript" src="scripts/mapdata.js"></script>
<script type="text/javascript" src="scripts/areadata.js"></script>
<script type="text/javascript" src="scripts/areacorners.js"></script>
<script type="text/javascript" src="scripts/scale.js"></script>
<script type="text/javascript" src="scripts/tooltip.js"></script>



<script src="scripts/canvas/base64.js"></script>
<script src="scripts/canvas/canvas2image.js"></script>



<script type="text/javascript">
		
		
	function saveCanvas(oCanvas, strType) {
		var bRes = false;
		if (strType == "PNG")
			bRes = Canvas2Image.saveAsPNG(oCanvas);
	

		if (!bRes) {
			alert("Sorry, this browser is not capable of saving " + strType + " files!");
			return false;
		}
	}
	
		function convertCanvas(strType) {
		if (strType == "PNG")
			var oImg = Canvas2Image.saveAsPNG(oCanvas, true);
		
		if (!oImg) {
			alert("Sorry, this browser is not capable of saving " + strType + " files!");
			return false;
		}

		oImg.id = "canvasimage";

		oImg.style.border = oCanvas.style.border;
		document.body.replaceChild(oImg, oCanvas);

		showDownloadText();
	}
	
	//used
	
	function setToolTipCheckBoxEvent(data) {
        // Get the two checkboxes within the 
        var checkBoxes = data.toolTip.find('input');

        checkBoxes.each(function () {

            var span = $(this).parent().find('span');

            span.unbind('click').bind('click', function (e) {
                var chk = $(this).parent().find('input');
                var isChecked = chk.is(':checked');
                // want to do opposite of what the check box is
                // set the checkbox
                chk.attr('checked', !isChecked);
                doCheckBoxAreaAction(chk, !isChecked);
            });

            var ttName = $(this).attr('name');

            var checkBoxId = getFullCheckBoxID($(this));
            var listCheckbox = $jointlist.find('#jl_' + checkBoxId);

            if (listCheckbox.attr('name') == ttName) {
                $(this).attr('checked', listCheckbox.attr('checked'));
            }

            // return the list to mapster so it can bind to it
            return $(this).unbind('click').click(function (e) {
                var selected = $(this).is(':checked');
                doCheckBoxAreaAction($(this), selected);
            });
        });
    }
	
	
	
	function buildAreas() {
        var items = $('#jointMap').find('area');
        var areaArray = [];

        items.each(function () {

            var areaName = $(this).attr('joint');
            var fullName = $(this).attr('full');
            areaArray.push({ key: areaName, toolTip: buildToolTipArea(areaName, fullName) });
        });
        return areaArray;
    }

    function buildToolTipArea(name, fullName) {
        return $('<div id="' + name + '_divID"><div>' + fullName + '</div><div>' +
            '<div><input id="tt_' + name + '_swol" type="checkbox" name="' + name + '" /><span class="sel" key="' + name + '">Swelling</span></div>' +
            '<div><input id="tt_' + name + '_tend" type="checkbox" name="' + name + '" /><span class="sel" key="' + name + '">Tender</span></div></div></div>');
    }

	
	
	
	// color: the color to render the area
	// selected: true or false
	function setState($joint_map,selected, key, color) {
		
		$joint_map.mapster('set',selected,key, {fillColor: color } );
	}
	
  $(function() {
	
	 var $jointlist, $joint_map, default_options, mapsterConfigured;
	 
	//create the virgin map
	var $joint_map=$('#myimage');
	
	    default_options =
        {
            fillOpacity: 0.5,
            render_highlight: {
                fillColor: 'ff000c',
                stroke: true
            },
            render_select: {
                fillColor: 'ff000c',
                stroke: false
            },

            fadeInterval: 50,
			mapKey: 'data-key',
			listKey: 'name',
            listSelectedAttribute: 'checked',
            sortList: false,
            showToolTip: true,
			
            //toolTipClose: toolTipCloseOptions,
            //onShowToolTip: setToolTipCheckBoxEvent,
            areas: buildAreas()
			
        };


   	$joint_map.mapster(default_options);
	
	 
	//init or get the active keys from DB
	var stateData = {};
	var activeStates = '{"states" : [{ "key": "A11" , "fillColor":"ff0000" , "fillOpacity": "0.3" },{   "key": "A12" , "fillColor":"fff000","fillOpacity": "0.3" }]}';
	
	//var donnees = eval('('+activeStates+')');
	
	var jsonData = JSON.parse(activeStates);
	for (var i in jsonData.states) {
		var state = jsonData.states[i];
		console.log(state.key);
		setState($joint_map,true,state.key,state.fillColor);
		
		// $joint_map.mapster('highlight',state.key)  ;
	
	}
	
	
	
	    /* fillColor: 'ff0000',
        fillOpacity: 0.3,*/
	/*
	$joint_map.mapster({
 
		mapKey: 'data-key'
    });*/
	
	//console.log(donnees);
	//set the active key to true with its correspondant value FillColor
	
	
	
	//$joint_map.mapster('set',true,activeStates);
	
		
	/*$('area').mapster('set',true,'A11');
	
	$('area').mapster('deselect');
		for(var obj in donnees) {

		console.log(obj.key);
	   //setState($joint_map,true,obj.key,obj.fillcolor);
	   //	$joint_map.mapster('set',selected,key, {fillColor: color } );
	   
	   //$joint_map.mapster('set',true,obj.key);
	}
	*/
	
	
	//save the state of the map to the DB
	$( "#savepngbtn" ).click(function() {
	 	activeKeys = $joint_map.mapster('get');
		alert(activeKeys);
		//make an ajax call to store states in DB;
		
	});
	
	
	/*
	var stateData = {}, $joint_map = $('#my$joint_map');
	
	

		
	var activeKeys = $joint_map.mapster('get');   // returns a comma-separated list
	alert(activeKeys);
	var activeStates = [];
	for(var key in activeKeys.split(',')) {
	   activeStates.push({
		  state: key,
		  color: stateData[key]
	   });
}
	*/

	/*var oCanvas = $('.mapster_el');

	var iWidth = oCanvas.width;
	var iHeight = oCanvas.height;
	*/
	

	/*var oCanvas = $(".mapster_el");

	var iWidth = oCanvas.width;
	var iHeight = oCanvas.height;
	
	*/

	


	
	
	
	
});
</script>	
	




<IMG  id="myimage" SRC="systeme_de_numerotation_dentaire_fdi.jpg" USEMAP="#jointMap">

<map id="jointMap" name="jointMap">
<area  data-key="A11" joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="222,60,222,60,222,62,222,63,221,65,221,67,220,68,219,69,218,71,217,72,215,73,214,74,213,75,211,75,209,76,208,76,206,76,204,76,203,76,201,75,199,75,198,74,197,73,195,72,194,71,193,69,192,68,191,67,191,65,190,63,190,62,190,60,190,58,190,57,191,55,191,53,192,52,193,51,194,49,195,48,197,47,198,46,199,45,201,45,203,44,204,44,206,44,208,44,209,44,211,45,213,45,214,46,215,47,217,48,218,49,219,51,220,52,221,53,221,55,222,57,222,58,222,60" href="" target="_self" />
<area  data-key="A12" joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="192,64,192,64,192,65,192,67,191,68,191,69,190,71,190,72,189,73,188,74,187,75,186,75,184,76,183,76,182,77,180,77,179,77,178,77,176,77,175,76,174,76,173,75,171,75,170,74,169,73,168,72,168,71,167,69,167,68,166,67,166,65,166,64,166,63,166,61,167,60,167,59,168,58,168,56,169,55,170,54,171,53,173,53,174,52,175,52,176,51,178,51,179,51,180,51,182,51,183,52,184,52,186,53,187,53,188,54,189,55,190,56,190,57,191,59,191,60,192,61,192,63,192,64" href="" target="" />
<area  data-key="A13" joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="146,64,140,76,146,84,164,90,172,86,162,63" href="" target="" />
<area  data-key="A14" joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="153,93,158,102,150,112,134,109,124,98,129,84,143,84" href="" target="" />
<area  data-key="A15" joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="114,111,112,121,123,132,140,132,148,123,129,106,129,106" href="" target="" />
<area  data-key="A16" joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="137,141,137,154,126,171,115,170,98,156,104,131,120,128" href="" target="" />
<area  data-key="A17" joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="126,183,126,183,126,185,126,186,125,188,125,190,124,191,123,192,122,194,121,195,119,196,118,197,117,198,115,198,113,199,112,199,110,199,108,199,107,199,105,198,103,198,102,197,101,196,99,195,98,194,97,192,96,191,95,190,95,188,94,186,94,185,94,183,94,181,94,180,95,178,95,176,96,175,97,174,98,172,99,171,101,170,102,169,103,168,105,168,107,167,108,167,110,167,112,167,113,167,115,168,117,168,118,169,119,170,121,171,122,172,123,174,124,175,125,176,125,178,126,180,126,181,126,183" href="" target="" />
<area  data-key="A18" joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="116,211,116,211,116,212,116,214,115,215,115,217,114,218,113,219,112,220,111,221,110,222,109,223,108,224,106,224,105,225,103,225,102,225,101,225,99,225,98,224,96,224,95,223,94,222,93,221,92,220,91,219,90,218,89,217,89,215,88,214,88,212,88,211,88,210,88,208,89,207,89,205,90,204,91,203,92,202,93,201,94,200,95,199,96,198,98,198,99,197,101,197,102,197,103,197,105,197,106,198,108,198,109,199,110,200,111,201,112,202,113,203,114,204,115,205,115,207,116,208,116,210,116,211" href="" target="" />
<area  data-key="AZ"  joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="251,59,251,59,251,61,251,62,250,64,250,65,249,67,248,68,247,69,246,70,245,71,244,72,242,73,241,73,239,74,238,74,236,74,234,74,233,74,231,73,230,73,229,72,227,71,226,70,225,69,224,68,223,67,222,65,222,64,221,62,221,61,221,59,221,57,221,56,222,54,222,53,223,52,224,50,225,49,226,48,227,47,229,46,230,45,231,45,233,44,234,44,236,44,238,44,239,44,241,45,242,45,244,46,245,47,246,48,247,49,248,50,249,51,250,53,250,54,251,56,251,57,251,59" href="" target="" />
<area  data-key="AZ"  joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="274,66,274,66,274,67,274,69,273,70,273,71,272,73,272,74,271,75,270,76,269,77,268,77,266,78,265,78,264,79,262,79,261,79,260,79,258,79,257,78,256,78,255,77,253,77,252,76,251,75,250,74,250,73,249,71,249,70,248,69,248,67,248,66,248,65,248,63,249,62,249,61,250,60,250,58,251,57,252,56,253,55,255,55,256,54,257,54,258,53,260,53,261,53,262,53,264,53,265,54,266,54,268,55,269,55,270,56,271,57,272,58,272,59,273,61,273,62,274,63,274,65,274,66" href="" target="" />
<area  data-key="AZ"  joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="297,79,297,79,297,80,297,82,296,83,296,84,295,86,295,87,294,88,293,89,292,90,291,90,289,91,288,91,287,92,285,92,284,92,283,92,281,92,280,91,279,91,278,90,276,90,275,89,274,88,273,87,273,86,272,84,272,83,271,82,271,80,271,79,271,78,271,76,272,75,272,74,273,73,273,71,274,70,275,69,276,68,278,68,279,67,280,67,281,66,283,66,284,66,285,66,287,66,288,67,289,67,291,68,292,68,293,69,294,70,295,71,295,73,296,74,296,75,297,76,297,78,297,79" href="" target="" />
<area  data-key="AZ"  joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="284,100,281,109,290,115,310,112,317,98,306,89,296,87,294,88,294,88" href="" target="" />
<area  data-key="AZ"  joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="296,121,293,128,297,135,308,137,320,134,328,124,324,115,315,111,306,113" href="" target="" />
<area  data-key="AZ"  joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="339,155,339,155,339,157,339,159,338,161,337,162,337,164,336,166,334,167,333,168,332,170,330,171,328,171,327,172,325,173,323,173,321,173,319,173,317,173,315,172,314,171,312,171,310,170,309,168,308,167,306,166,305,164,305,162,304,161,303,159,303,157,303,155,303,153,303,151,304,149,305,148,305,146,306,144,308,143,309,142,310,140,312,139,314,139,315,138,317,137,319,137,321,137,323,137,325,137,327,138,328,139,330,139,332,140,333,142,334,143,336,144,337,146,337,148,338,149,339,151,339,153,339,155" href="" target="" />
<area  data-key="AZ"  joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="343,188,343,188,343,190,343,191,342,193,342,194,341,196,340,197,339,198,338,199,337,200,336,201,334,202,333,202,331,203,330,203,328,203,326,203,325,203,323,202,322,202,321,201,319,200,318,199,317,198,316,197,315,196,314,194,314,193,313,191,313,190,313,188,313,186,313,185,314,183,314,182,315,181,316,179,317,178,318,177,319,176,321,175,322,174,323,174,325,173,326,173,328,173,330,173,331,173,333,174,334,174,336,175,337,176,338,177,339,178,340,179,341,181,342,182,342,183,343,185,343,186,343,188" href="" target="" />
<area  data-key="AZ"  joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="348,217,348,217,348,218,348,220,347,221,347,223,346,224,345,225,344,226,343,227,342,228,341,229,340,230,338,230,337,231,335,231,334,231,333,231,331,231,330,230,328,230,327,229,326,228,325,227,324,226,323,225,322,224,321,223,321,221,320,220,320,218,320,217,320,216,320,214,321,213,321,211,322,210,323,209,324,208,325,207,326,206,327,205,328,204,330,204,331,203,333,203,334,203,335,203,337,203,338,204,340,204,341,205,342,206,343,207,344,208,345,209,346,210,347,211,347,213,348,214,348,216,348,217" href="" target="" />
<area  data-key="AZ"  joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="237,473,237,473,237,474,237,475,237,476,236,477,236,478,235,479,234,480,234,480,233,481,232,482,231,482,230,483,229,483,228,483,227,483,226,483,225,483,224,483,223,482,222,482,221,481,220,480,220,480,219,479,218,478,218,477,217,476,217,475,217,474,217,473,217,472,217,471,217,470,218,469,218,468,219,467,220,466,220,466,221,465,222,464,223,464,224,463,225,463,226,463,227,463,228,463,229,463,230,463,231,464,232,464,233,465,234,466,234,466,235,467,236,468,236,469,237,470,237,471,237,472,237,473" href="" target="" />
<area  data-key="AZ"  joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="258,470,258,470,258,471,258,472,257,473,257,474,257,476,256,476,255,477,254,478,253,479,253,480,251,480,250,480,249,481,248,481,247,481,246,481,245,481,244,480,243,480,242,480,241,479,240,478,239,477,238,476,237,476,237,474,237,473,236,472,236,471,236,470,236,469,236,468,237,467,237,466,237,465,238,464,239,463,240,462,241,461,242,460,243,460,244,460,245,459,246,459,247,459,248,459,249,459,250,460,251,460,253,460,253,461,254,462,255,463,256,464,257,465,257,466,257,467,258,468,258,469,258,470" href="" target="" />
<area  data-key="AZ"  joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="279,462,279,462,279,463,279,464,278,465,278,466,278,468,277,468,276,469,275,470,274,471,274,472,272,472,271,472,270,473,269,473,268,473,267,473,266,473,265,472,264,472,263,472,262,471,261,470,260,469,259,468,258,468,258,466,258,465,257,464,257,463,257,462,257,461,257,460,258,459,258,458,258,457,259,456,260,455,261,454,262,453,263,452,264,452,265,452,266,451,267,451,268,451,269,451,270,451,271,452,272,452,274,452,274,453,275,454,276,455,277,456,278,457,278,458,278,459,279,460,279,461,279,462" href="" target="" />
<area  data-key="AZ"  joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="296,445,296,445,296,446,296,447,295,449,295,450,294,451,294,452,293,453,292,454,291,455,290,455,289,456,288,456,286,457,285,457,284,457,283,457,282,457,280,456,279,456,278,455,277,455,276,454,275,453,274,452,274,451,273,450,273,449,272,447,272,446,272,445,272,444,272,443,273,441,273,440,274,439,274,438,275,437,276,436,277,435,278,435,279,434,280,434,282,433,283,433,284,433,285,433,286,433,288,434,289,434,290,435,291,435,292,436,293,437,294,438,294,439,295,440,295,441,296,443,296,444,296,445" href="" target="" />
<area  data-key="AZ"  joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="327,387,325,402,316,417,300,416,287,406,288,396,299,379,309,377" href="" target="" />
<area  data-key="AZ"  joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="336,351,340,362,330,379,319,382,307,377,301,368,307,350,320,344" href="" target="" />
<area  data-key="AZ"  joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="347,330,347,330,347,332,347,334,346,335,346,337,345,339,344,340,343,341,341,343,340,344,339,345,337,346,335,346,334,347,332,347,330,347,328,347,326,347,325,346,323,346,322,345,320,344,319,343,317,341,316,340,315,339,314,337,314,335,313,334,313,332,313,330,313,328,313,326,314,325,314,323,315,322,316,320,317,319,319,317,320,316,322,315,323,314,325,314,326,313,328,313,330,313,332,313,334,313,335,314,337,314,339,315,340,316,341,317,343,319,344,320,345,322,346,323,346,325,347,326,347,328,347,330" href="" target="" />
<area  data-key="AZ"  joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="216,471,216,471,216,472,216,473,215,474,215,475,215,477,214,477,213,478,212,479,211,480,211,481,209,481,208,481,207,482,206,482,205,482,204,482,203,482,202,481,201,481,200,481,199,480,198,479,197,478,196,477,195,477,195,475,195,474,194,473,194,472,194,471,194,470,194,469,195,468,195,467,195,466,196,465,197,464,198,463,199,462,200,461,201,461,202,461,203,460,204,460,205,460,206,460,207,460,208,461,209,461,211,461,211,462,212,463,213,464,214,465,215,466,215,467,215,468,216,469,216,470,216,471" href="" target="" />
<area  data-key="AZ"  joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="194,467,194,467,194,468,194,469,193,470,193,471,193,473,192,473,191,474,190,475,189,476,189,477,187,477,186,477,185,478,184,478,183,478,182,478,181,478,180,477,179,477,178,477,177,476,176,475,175,474,174,473,173,473,173,471,173,470,172,469,172,468,172,467,172,466,172,465,173,464,173,463,173,462,174,461,175,460,176,459,177,458,178,457,179,457,180,457,181,456,182,456,183,456,184,456,185,456,186,457,187,457,189,457,189,458,190,459,191,460,192,461,193,462,193,463,193,464,194,465,194,466,194,467" href="" target="" />
<area  data-key="AZ"  joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="175,460,175,460,175,461,175,462,174,463,174,464,174,466,173,466,172,467,171,468,170,469,170,470,168,470,167,470,166,471,165,471,164,471,163,471,162,471,161,470,160,470,159,470,158,469,157,468,156,467,155,466,154,466,154,464,154,463,153,462,153,461,153,460,153,459,153,458,154,457,154,456,154,455,155,454,156,453,157,452,158,451,159,450,160,450,161,450,162,449,163,449,164,449,165,449,166,449,167,450,168,450,170,450,170,451,171,452,172,453,173,454,174,455,174,456,174,457,175,458,175,459,175,460" href="" target="" />
<area  data-key="AZ"  joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="160,443,160,443,160,444,160,445,159,447,159,448,158,449,158,450,157,451,156,452,155,453,154,453,153,454,152,454,150,455,149,455,148,455,147,455,146,455,144,454,143,454,142,453,141,453,140,452,139,451,138,450,138,449,137,448,137,447,136,445,136,444,136,443,136,442,136,441,137,439,137,438,138,437,138,436,139,435,140,434,141,433,142,433,143,432,144,432,146,431,147,431,148,431,149,431,150,431,152,432,153,432,154,433,155,433,156,434,157,435,158,436,158,437,159,438,159,439,160,441,160,442,160,443" href="" target="" />
<area  data-key="AZ"  joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="150,422,150,422,150,423,150,425,149,426,149,427,148,429,148,430,147,431,146,432,145,433,144,433,142,434,141,434,140,435,138,435,137,435,136,435,134,435,133,434,132,434,131,433,129,433,128,432,127,431,126,430,126,429,125,427,125,426,124,425,124,423,124,422,124,421,124,419,125,418,125,417,126,416,126,414,127,413,128,412,129,411,131,411,132,410,133,410,134,409,136,409,137,409,138,409,140,409,141,410,142,410,144,411,145,411,146,412,147,413,148,414,148,416,149,417,149,418,150,419,150,421,150,422" href="" target="" />
<area  data-key="AZ"  joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="147,393,147,393,147,395,147,397,146,399,145,401,144,403,143,404,142,406,141,407,139,408,138,409,136,410,134,411,132,412,130,412,128,412,126,412,124,412,122,411,120,410,119,409,117,408,115,407,114,406,113,404,112,403,111,401,110,399,109,397,109,395,109,393,109,391,109,389,110,387,111,385,112,384,113,382,114,380,115,379,117,378,118,377,120,376,122,375,124,374,126,374,128,374,130,374,132,374,134,375,136,376,138,377,139,378,141,379,142,380,143,382,144,384,145,385,146,387,147,389,147,391,147,393" href="" target="" />
<area  data-key="AZ"  joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="133,359,133,359,133,361,133,363,132,364,132,366,131,368,130,369,129,370,127,372,126,373,125,374,123,375,121,375,120,376,118,376,116,376,114,376,112,376,111,375,109,375,108,374,106,373,105,372,103,370,102,369,101,368,100,366,100,364,99,363,99,361,99,359,99,357,99,355,100,354,100,352,101,351,102,349,103,348,105,346,106,345,108,344,109,343,111,343,112,342,114,342,116,342,118,342,120,342,121,343,123,343,125,344,126,345,127,346,129,348,130,349,131,351,132,352,132,354,133,355,133,357,133,359" href="" target="" />
<area  data-key="AZ"  joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="124,325,124,325,124,327,124,328,123,330,123,332,122,333,121,334,120,336,119,337,117,338,116,339,115,340,113,340,111,341,110,341,108,341,106,341,105,341,103,340,101,340,100,339,99,338,97,337,96,336,95,334,94,333,93,332,93,330,92,328,92,327,92,325,92,323,92,322,93,320,93,318,94,317,95,316,96,314,97,313,99,312,100,311,101,310,103,310,105,309,106,309,108,309,110,309,111,309,113,310,115,310,116,311,117,312,119,313,120,314,121,316,122,317,123,318,123,320,124,322,124,323,124,325" href="" target="" />
<area  data-key="AZ"  joint="L_TMJ"  full="Left TMJ" shape="poly" alt="" title="" coords="287,414,278,424,282,431,296,439,307,436,312,428,306,418,298,414,287,412" href="" target="" /><!-- Created by Online Image Map Editor (http://www.maschek.hu/$joint_mapmap/index) -->
</map>
<input type="button" id="savepngbtn" value="Save PNG">
<input type="button" id="convertpngbtn" value="Convert to PNG">
<br/>