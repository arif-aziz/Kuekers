(function(a){a(window).load(function(){a('div[id^="wr-'+wr_nitro_customize_typography.type+'-"]').each(function(){var c=a(this);c.id=c.attr("id").replace("wr-"+wr_nitro_customize_typography.type+"-","");var f=wp.customize.control(c.id).setting.get(),b=[];c.input_color=c.find('input[name="color"]');c.find(".wr-image-selected").click(function(i){var j=a(this);j.next().toggle();window.wr_click_outside(j,".customize-control-select",function(k){j.next().hide()});i.stopPropagation()});var h=c.find(".google-fonts-list");if(h.length){for(var d in wr_nitro_customize_typography.google_fonts){var e=a("<li />").appendTo(h);e.addClass("wr-select-image "+d.toLowerCase().replace(/\s+/g,"-"));e.addClass(f.family==d?"selected":"");e.attr("data-value",d);e.html("<span>"+d+"</span>");if(f.family==d){b=wr_nitro_customize_typography.google_fonts[d]}}}var g=c.find('select[name="fontWeight"]');if(b.length>1){g.parent().show().find("option").each(function(j,k){if(b.indexOf(parseInt(a(k).attr("value")))>-1){a(k).show();if(parseInt(a(k).attr("value"))==parseInt(f.fontWeight)){g.val(a(k).attr("value"))}}else{a(k).hide()}})}else{g.val(wr_nitro_customize_typography.default_font_weight).parent().hide()}c.find(".wr-select-image").click(function(){var k=a(this);if(!k.hasClass("selected")){var i=k.attr("data-value");var j=i.toLowerCase().replace(/\s+/g,"-");c.find(".wr-image-selected span").text(i);c.find(".wr-image-selected").attr("class","").addClass("wr-image-selected "+j);c.find(".wr-select-image.selected").removeClass("selected");k.addClass("selected");b=wr_nitro_customize_typography.google_fonts[i];if(wr_nitro_customize_typography.google_fonts[i].length==1){g.val(wr_nitro_customize_typography.default_font_weight).parent().hide()}else{g.parent().show().find("option").each(function(l,m){if(b.indexOf(parseInt(a(m).attr("value")))>-1){a(m).show()}else{a(m).hide()}});if(b.indexOf(g.val())<0){g.val(wr_nitro_customize_typography.default_font_weight)}}c.find(".data-family").val(i).trigger("change")}k.closest(".wr-select-image-container").hide()});c.on("click",'input[type="checkbox"]',function(){a(this).parent()[a(this).attr("checked")?"addClass":"removeClass"]("active")});c.on("change","ul, input[name], select[name]",function(){var i={};c.find("ul, input[name], select[name]").each(function(k,l){if(a(l).attr("name")!==undefined){if(a(l).attr("type")=="checkbox"){i[a(l).attr("name")]=a(l).attr("checked")?1:0}else{if(a(l).attr("type")=="radio"){if(a(l).attr("checked")){i[a(l).attr("name")]=a(l).val()}}else{if(a(l).attr("name")=="color"){var j=a(l).spectrum("get");i[a(l).attr("name")]=j?(j.getAlpha()==1?j.toHexString():j.toRgbString()):""}else{i[a(l).attr("name")]=a(l).val()}}}}});wp.customize.control(c.id).setting.set(i)})});a("body").on("keyup",".wr-select-image-container .txt-sfont",function(c){var b=a(this).val();var d=a(this).closest(".wr-select-image-container").find("li");if(b){if(window.keyword_font_old==undefined||window.keyword_font_old!=b||c.keyCode==13||c.keyCode==86){d.hide();d.each(function(){var e=a(this).attr("data-value").toLowerCase();var f=b.toLowerCase().trim();if(e.indexOf(f)==-1){a(this).hide()}else{a(this).fadeIn(200)}});window.keyword_font_old=b}}else{d.show()}})})})(jQuery);