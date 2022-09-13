<script type="text/javascript">

    function isDigit(c)
    {
        return c>=48 && c<=57;
    }

    function isUpper(c)
    {
        return c>=65 && c<=90;
    }

    function isLower(c)
    {
        return c>=97 && c<=122;
    }

    function keyInput(event)
    {
        var k = event ? event.which : window.event.keyCode;
        if(!(isDigit(k) || isUpper(k) || isLower(k) || k==95)) return false;
    }

    function intInput(event)
    {
        var k = event ? event.which : window.event.keyCode;
        if(!isDigit(k)) return false;
    }

    function enableTab(id)
    {
        var el = document.getElementById(id);
        el.onkeydown = function(e) 
        {
            if(e.keyCode === 9) // tab was pressed
            {
                // get caret position/selection
                var val = this.value,
                    start = this.selectionStart,
                    end = this.selectionEnd;

                // set textarea value to: text before caret + tab + text after caret
                this.value = val.substring(0, start) + '\t' + val.substring(end);

                // put caret at right position again
                this.selectionStart = this.selectionEnd = start + 1;

                // prevent the focus lose
                return false;
            }
        }
    }
</script>