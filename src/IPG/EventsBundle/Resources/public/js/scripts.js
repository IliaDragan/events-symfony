/**
 * Created by mm on 4/18/14.
 */
var $collectionHolder;

(function($) {
    $(document).ready(function() {
        var $collectionHolder;

        // setup an "add a category" link
        var $addCategoryLink = $('<a href="#" class="add_category_link">Add a category</a>');
        var $newLinkLi = $('<li></li>').append($addCategoryLink);

        jQuery(document).ready(function() {
            // Get the ul that holds the collection of categories
            $collectionHolder = $('ul.categories');

            $collectionHolder.find('li').each(function() {
                addCategoryFormDeleteLink($(this));
            });

            // add the "add a category" anchor and li to the categories ul
            $collectionHolder.append($newLinkLi);

            // count the current form inputs we have (e.g. 2), use that as the new
            // index when inserting a new item (e.g. 2)
            $collectionHolder.data('index', $collectionHolder.find(':input').length);

            $addCategoryLink.on('click', function(e) {
                // prevent the link from creating a "#" on the URL
                e.preventDefault();

                // add a new category form (see next code block)
                addCategoryForm($collectionHolder, $newLinkLi);
            });
        });
    });

    function addCategoryForm($collectionHolder, $newLinkLi) {
        // Get the data-prototype explained earlier
        var prototype = $collectionHolder.data('prototype');

        // get the new index
        var index = $collectionHolder.data('index');

        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        var newForm = prototype.replace(/__name__/g, index);

        // increase the index with one for the next item
        $collectionHolder.data('index', index + 1);

        // Display the form in the page in an li, before the "Add a category" link li
        var $newFormLi = $('<li></li>').append(newForm);
        $newLinkLi.before($newFormLi);

        addCategoryFormDeleteLink($newFormLi);
    }

    function addCategoryFormDeleteLink($categoryFormLi) {
        var $removeFormA = $('<a href="#">delete this category</a>');
        $categoryFormLi.append($removeFormA);

        $removeFormA.on('click', function(e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();

            // remove the li for the category form
            $categoryFormLi.remove();
        });
    }
})(jQuery);

