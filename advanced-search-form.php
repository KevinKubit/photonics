<form method="get" action="<?php bloginfo('siteurl'); ?>" class="advanced-search">
  <fieldset>
  <legend>General Search Options</legend>
  <label for="search_terms"> Search Terms:
  <textarea id="search_terms" name="s"></textarea>
  </label>
  <label for="search_category">Category:
  <select id="search_category" name="search_category">
    <option>Entire Site</option>
    <option>Publications</option>
  </select>
  </label>
  </fieldset>
  <fieldset id="publication-options" class="category-options">
  <legend>Category Filters</legend>
  <label for="search_publication_category"> Publication Category:
  <select disabled="disabled" name="search_publication_category" id="search_publication_category">
    <option value="0">All</option>
    <option>Journal Articles</option>
    <option>Published Conference Proceedings</option>
    <option>Conference Presentations</option>
    <option>Theses</option>
    <option>Miscellaneous Presentations</option>
    <option>Patents</option>
  </select>
  </label>
  <label for="search_publication_authors"> Only return articles written by:
  <input disabled="disabled" type="text" name="search_publication_authors" id="search_publications_authors" />
  </label>
  </fieldset>
  <fieldset id="publication-sort" class="category-options sorting-options">
  <legend>Sort Options</legend>
  <label for="search_sort">Sort By: 
  <select name="search_sort">
    <option value="0">Default (Post Date)</option>
    <option value="publication_date">Publication Date</option>
    <option value="title">Title</option>
    <option value="identifier">Identifier</option>
    <option value="doi">DOI</option>
    <option value="authors">Authors</option>
  </select>
  </label>
  <label for="search_sort_direction">Sort Direction: 
  <select name="search_sort_direction">
  	<option value="DESC">Descending</option>
    <option value="ASC">Ascending</option>
  </select>
  </label>
  </fieldset>
  <fieldset id="entire_site-sort" class="category-options sorting-options">
  <legend>Sort Options</legend>
  <label for="search_sort">Sort By: 
  <select name="search_sort">
    <option value="0">Default (Post Date)</option>
    <option value="title">Title</option>
  </select>
  </label>
  <label for="search_sort_direction">Sort Direction: 
  <select name="search_sort_direction">
  	<option value="DESC">Descending</option>
    <option value="ASC">Ascending</option>
  </select>
  </label>
  </fieldset>
  <input type="submit" value="Search" id="advanced-search-button" />
</form>
