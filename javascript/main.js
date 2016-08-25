/**
 * DiffText.
 * User: demorfi@gmail.com
 */

$(function ()
{

    /**
     * Display changed text.
     */
    $('.offer-change').on({
        'mouseenter': function ()
        {
            $(this).css({
                'minHeight': $(this).css('height'),
                'minWidth' : $(this).css('width')
            }).text($(this).data('old'));
        },
        'mouseleave': function ()
        {
            $(this).text($(this).data('new'));
        }
    });
});