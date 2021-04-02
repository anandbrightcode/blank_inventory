(function( $ ){
    $.fn.typeselect = function() {
        $this=$(this);
        $this.hide();
        $this.parent().find('.toselect').remove();
        $this.parent().find('.selectlist').remove();
        $this.parent().append('<input type="text" class="form-control toselect" placeholder="">');
        $this.parent().append("<div class='selectlist'></div>");
        $('body').on('keyup','.toselect',function(e){
            e.preventDefault();
            $this=$(this);
            if($this.is('[readonly]') || $this.is('[readonly="readonly"]')){
                return false;
            }
            if($this.is('input')){
                $input=$this;
                $select=$this.siblings('select');
                $listDiv=$this.siblings('div');
            }else{
                $input=$this.closest('div').siblings('input');
                $select=$this.closest('div').siblings('select');
                $listDiv=$this.closest('div');
            }
            var toSearch=$input.val();
            var pos=$('.toselect').index(this);
            if(e.which==40){
                pos++;
                if(pos<$('.toselect').length){
                    $('.toselect').eq(pos).focus();
                }
            }
            else if(e.which==38){
                pos--;
                if(pos>=0){
                    $('.toselect').eq(pos).focus();
                }
            }
            else if(e.which==13){
                $input.val( $this.text());
                $select.val( $this.val()).trigger('change');
                 $listDiv.html('');
            }
            else{
                if( $this.is('button')){
                    var char=String.fromCharCode(e.which);
                    char=char.toLowerCase();
                    if(char.match(/[\w]/)!==null){
                         $this.closest('div').siblings('input').val( function( index, val ) {
                            return val + char;
                        });
                        toSearch+=char;
                    }
                    $input.focus();
                }
                $listDiv.html('');
                var array=[];
                toSearch = toSearch.replace(/[^a-zA-Z0-9]/g,"\\$&");
                var patt1 = new RegExp(toSearch,'i'); 
                var i=0;
                $select.children().each(function(){
                    value=$(this).text();
                    if($(this).val()!='' && value.match(patt1)!==null){
                        //array.push({"id":$(this).val(),"value":value});
                        var button=$("<button type='button' class='toselect' value='"+$(this).val()+"'>"+value+"</button>").css("width",$this.outerWidth());
                        $listDiv.append(button);
                        i++;
                        if(i>4){
                            return false;
                        }
                    }
                });
            }

        });
        $('body').on('click','.toselect',function(e){
            $(this).closest('div').siblings('select').val('');
            if($(this).is('button')){
                $(this).closest('div').siblings('input').val($(this).text());
                $(this).closest('div').siblings('select').val($(this).val()).trigger('change');
                $(this).closest('div').html('');
            }
        });
        $('body').on('click',function(e){
            if(!e.target.classList.contains('toselect')){
                $('.selectlist').html('');
            }
        });
    }; 
})( jQuery );