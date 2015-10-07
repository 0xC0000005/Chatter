require(["dojo/ready", "dojo/dom-class", "dojo/on", "dojo/dom", 
    "dijit/form/Textarea"
		 ],
function(ready, domClass, on, dom,
	textarea ) {
	
    //When DOM is ready
    ready(function(){


		var area = new textarea({
			name: 'message'
		}, 'new_post');

		on (dom.byId("insert_tag_img"), "click", function(){
		    var cur = area.get("cursorPosition");
		    var val = area.get("value");
		    if (cur[0] != cur[1]) {
		        var str = val.substring(0,cur[0])+ '[IMG]' + val.substring(cur[0],cur[1]) + '[/IMG]' + val.substring(cur[1]);
		    } else {
		        var str = val.substring(0,cur[0])+ '[IMG]' + val.substring(cur[1] + '[/IMG]');
		    }
		    area.set("value", str);
		    area.focus();
		    area.textbox.setSelectionRange(
			cur[0]+'[IMG=""]'.length, 
			cur[0]+'[IMG=""]'.length);
		});
		on (dom.byId("insert_tag_url"), "click", function(){
		    var cur = area.get("cursorPosition");
		    var val = area.get("value");
		    if (cur[0] != cur[1]) {
		        var str = val.substring(0,cur[0])+ '[URL]' + val.substring(cur[0],cur[1]) + '[/URL]' + val.substring(cur[1]);
		    } else {
		        var str = val.substring(0,cur[0])+ '[URL]' + val.substring(cur[1] + '[/URL]');
		    }
		    area.set("value", str);
		    area.focus();
		    area.textbox.setSelectionRange(
			cur[0]+'[URL]'.length, 
			cur[0]+'[URL]'.length);
		});
		on (dom.byId("insert_tag_video"), "click", function(){
		    var cur = area.get("cursorPosition");
		    var val = area.get("value");
		    if (cur[0] != cur[1]) {
		        var str = val.substring(0,cur[0])+ '[VIDEO]' + val.substring(cur[0],cur[1]) + '[/VIDEO]' + val.substring(cur[1]);
		    } else {
		        var str = val.substring(0,cur[0])+ '[VIDEO]' + val.substring(cur[1] + '[/VIDEO]');
		    }
		    area.set("value", str);
		    area.focus();
		    area.textbox.setSelectionRange(
			cur[0]+'[VIDEO]'.length, 
			cur[0]+'[VIDEO]'.length);
		});
		on (dom.byId("insert_tag_fontsize"), "click", function(){
		    var cur = area.get("cursorPosition");
		    var val = area.get("value");
		    if (cur[0] != cur[1]) {
		        var str = val.substring(0,cur[0])+ '[SIZE="' + dom.byId("selected_fontsize").value + '"]' + val.substring(cur[0],cur[1]) + '[/SIZE]' + val.substring(cur[1]);
		    } else {
		        var str = val.substring(0,cur[0])+ '[SIZE="' + dom.byId("selected_fontsize").value +'"][/SIZE]' + val.substring(cur[1]);
		    }
		    area.set("value", str);
		    area.focus();
		    area.textbox.setSelectionRange(
			cur[0]+'[SIZE="' + dom.byId("selected_fontsize").value + '"]'.length, 
			cur[0]+'[SIZE="' + dom.byId("selected_fontsize").value + '"]'.length);
		});
		
		on (dom.byId("insert_tag_bold"), "click", function(){
		    var cur = area.get("cursorPosition");
		    var val = area.get("value");
		    if (cur[0] != cur[1]) {
		        var str = val.substring(0,cur[0])+ '[B]' + val.substring(cur[0],cur[1]) + '[/B]' + val.substring(cur[1]);
		    } else {
		        var str = val.substring(0,cur[0])+ '[B]' + val.substring(cur[1] + '[/B]');
		    }
		    area.set("value", str);
		    area.focus();
		    area.textbox.setSelectionRange(
			cur[0]+'[IMG=""]'.length, 
			cur[0]+'[IMG=""]'.length);
		});

		on (dom.byId("insert_tag_italic"), "click", function(){
		    var cur = area.get("cursorPosition");
		    var val = area.get("value");
		    if (cur[0] != cur[1]) {
		        var str = val.substring(0,cur[0])+ '[I]' + val.substring(cur[0],cur[1]) + '[/I]' + val.substring(cur[1]);
		    } else {
		        var str = val.substring(0,cur[0])+ '[I]' + val.substring(cur[1] + '[/I]');
		    }
		    area.set("value", str);
		    area.focus();
		    area.textbox.setSelectionRange(
			cur[0]+'[IMG=""]'.length, 
			cur[0]+'[IMG=""]'.length);
		});
		
		area.on("blur", function() {
		    var start = area.textbox.selectionStart,
		        end = area.textbox.selectionEnd;
		    area.set("cursorPosition", [start, end]);
		});
        
		area.on("focus", function() {
		    var cursorPosition = area.get("cursorPosition");
		    if(cursorPosition) {            
		        area.textbox.setSelectionRange(
				cursorPosition[1], 
				cursorPosition[1]);                                          
		    }
		});
    });
});
