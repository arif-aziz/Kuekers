(function(a){a.WR_Editor_Control=function(c){var b=this;b.data=c;b.init()};a.WR_Editor_Control.prototype={init:function(){var b=this;b.container=a("#wr-"+wr_nitro_customize_editor.type+"-"+b.data.id);b.container.find('a[target="thickbox"]').click(function(){setTimeout(a.proxy(function(){a("#TB_overlay").css("z-index","999999");a("#TB_ajaxContent").html(a("#nitro_customize_control_editor_template").text());var c=wp.customize.control(b.data.id).setting.get();if(!c){c=b.data.placeholder}b.editor=CodeMirror(a("#TB_ajaxContent").find(".customize-control-editor .editor")[0],{value:c,mode:b.data.mode,autofocus:true,indentUnit:4,indentWithTabs:true,lineNumbers:true,showCursorWhenSelecting:true,lineWrapping:true});b.editor.on("change",function(){b.editor._changed=true});a("#TB_ajaxContent").find(".customize-control-editor").on("click","button",function(){if(a(this).hasClass("save")){wp.customize.control(b.data.id).setting.set(b.editor.getValue());a("#TB_closeWindowButton").trigger("click")}else{if(b.editor._changed){if(confirm(b.data.confirm_message)){a("#TB_closeWindowButton").trigger("click")}}else{a("#TB_closeWindowButton").trigger("click")}}});a("#TB_overlay").css("pointer-events","none");a("#TB_window").css("z-index","999999")},this),500)})}}})(jQuery);