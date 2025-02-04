
<div class="footer">
    <button id="scroll"><span style="display: none;">&#8593;</span></button>
</div>
<script>
var btn = $('#scroll');

$(window).scroll(function() {
    if ($(window).scrollTop() > 300) {
        btn.addClass('show');
    } else {
        btn.removeClass('show');
    }
});

btn.on('click', function(e) {
    e.preventDefault();
    $('html, body').animate({
        scrollTop: 0
    }, '300');
});
</script>