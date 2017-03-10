@mod @mod_socialwiki

Feature: Homepage
  Does the homepage look right?

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email               |
      | teacher1 | Teacher   | 1        | teacher@example.com |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1        | 0        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
    And the following "activities" exist:
      | activity   | course | idnumber | name        |
      | socialwiki | C1     | sw1      | Homepage SW |
    And I log in as "teacher1"
    And I follow "Course 1"
    And I follow "Homepage SW"

  Scenario: Initial Home Layout
    # Check above tabs.
    Then I should see "Social Wiki Home" in the "#socialwiki_content_area > h2" "css_element"
    And I should see "Teacher 1" in the ".home_user" "css_element"
    And ".home-picture a img" "css_element" should exist

    # Check Explore tab.
    Then I should see "Explore" in the ".nav-tabs .active" "css_element"
    And I should see "From Users You Follow" in the "#socialwiki_content_area > h2:nth-of-type(2)" "css_element"
    And I should see "No unseen page versions liked by the users you follow."
    And I should see "New Page Versions" in the "#socialwiki_content_area > h2:nth-of-type(3)" "css_element"
    And I should see "No new page versions."
    And I should see "All Page Versions" in the "#socialwiki_content_area > h2:nth-of-type(4)" "css_element"
    And I should see "No page versions."
    And ".datatable" "css_element" should not exist

    # Check Pages tab.
    When I follow "Pages"
    Then I should see "Pages" in the ".nav-tabs .active" "css_element"
    And I should see "All Pages" in the "#socialwiki_content_area > h2:nth-of-type(2)" "css_element"
    And I should see "No pages."
    And ".datatable" "css_element" should not exist
    And "//input[@id='id_submitbutton' and @value='Make a new Page']" "xpath_element" should exist
    And "#id_submitbutton" "css_element" should appear before "#socialwiki_content_area > h2:nth-of-type(2)" "css_element"

    # Check Manage tab.
    When I follow "Manage"
    Then I should see "Manage" in the ".nav-tabs .active" "css_element"
    And I should see "My Favourites" in the "#socialwiki_content_area > h2:nth-of-type(2)" "css_element"
    And I should see "You don't have any favourite pages."
    And I should see "My Likes" in the "#socialwiki_content_area > h2:nth-of-type(3)" "css_element"
    And I should see "You don't have any likes."
    And I should see "My Pages" in the "#socialwiki_content_area > h2:nth-of-type(4)" "css_element"
    And I should see "You haven't created any pages."
    And ".datatable" "css_element" should not exist

    # Check People tab.
    When I follow "People"
    Then I should see "People" in the ".nav-tabs .active" "css_element"
    And I should see "Followers" in the "#socialwiki_content_area > h2:nth-of-type(2)" "css_element"
    And I should see "You don't have any followers."
    And I should see "Following" in the "#socialwiki_content_area > h2:nth-of-type(3)" "css_element"
    And I should see "You don't follow anybody."
    And I should see "All Active Users" in the "#socialwiki_content_area > h2:nth-of-type(4)" "css_element"
    And I should see "No other users."
    And ".datatable" "css_element" should not exist