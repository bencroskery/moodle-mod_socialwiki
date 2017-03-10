@mod @mod_socialwiki

Feature: Edit page
  In order to use a specific format
  As a user
  All the toolbar buttons and tags need to output correctly

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

  @javascript
  Scenario Outline: Wikieditor
    Given the following "activities" exist:
      | activity   | course | idnumber | name    |
      | socialwiki | C1     | sw1      | Test SW |
    When I log in as "teacher1"
    And I follow "Course 1"
    And I follow "Test SW"
    And I follow "Pages"
    And I press "Make a new Page"

    # Create a page
    And I set the field "New page title" to "<format> Page"
    And I set the field "<format> format" to "1"
    And I press "Create page"

    # Use toolbar buttons
    Then ".socialwikieditor-toolbar" "css_element" should be visible
    When I click on "Bold text" "button"
    And I click on "Internal link" "button"
    And I click on "Italic text" "button"
    And I click on "External URL" "button"
    And I click on "Image" "button"
    And the field "newcontent" matches value "<content>"
    Then "#styleheads .dropdown-menu" "css_element" should not be visible
    And I click on "styleprops" "button"
    Then "#styleheads .dropdown-menu" "css_element" should be visible
    And I click on "Level 1 Header" "link"
    Then "#styleheads .dropdown-menu" "css_element" should not be visible
    And I click on "styleprops" "button"
    And I click on "Level 2 Header" "link"
    And I click on "styleprops" "button"
    And I click on "Level 3 Header" "link"
    And I click on "styleprops" "button"
    And I click on "Pre-formatted" "link"
    And I press "Save"

    # Check HTML
    Then I should see "Bold textInternal linkItalic texthttp://External URL"
    And ".wikipage img" "css_element" should exist
    And I should see "Level 1 Header" in the ".wikipage .text_to_html h3" "css_element"
    And I should see "Level 2 Header" in the ".wikipage .text_to_html h4" "css_element"
    And I should see "Level 3 Header" in the ".wikipage .text_to_html h5" "css_element"
    And I should see "Pre-formatted" in the ".wikipage .text_to_html pre" "css_element"
    And I should not see "<bold>" in the ".wikipage" "css_element"
    And I should not see "<italic>" in the ".wikipage" "css_element"
    And I should not see "=" in the ".wikipage" "css_element"

    Examples:
      | format | content                                                                                | bold | italic |
      | NWiki  | ''Bold text''[[Internal link]]'''Italic text'''http://External URL[[image:Image\|alt]] | ''   | '''    |
      | Creole | **Bold text**[[Internal link]]//Italic text//http://External URL{{Image\|Alt}}         | **   | //I    |

  Scenario Outline: Forced format + Nojs
    Given the following "activities" exist:
      | activity   | course | idnumber | name            | defaultformat | forceformat |
      | socialwiki | C1     | sw1      | Force Format SW | <format>      | 1           |
    When I log in as "teacher1"
    And I follow "Course 1"
    And I follow "Force Format SW"
    And I follow "Pages"
    And I press "Make a new Page"
    And I should not see "Format" in the "#socialwiki_content_area" "css_element"
    And I set the field "New page title" to "Forced <format> page"
    And I press "Create page"
    And I set the field "<format> format" to "<input> [[<format> format]]."
    And I press "Save"
    Then I should see "Forcing the use of the <format> format" in the ".wikipage" "css_element"
    And I should see "Forcing" in the ".wikipage strong" "css_element"
    And I should see "use of" in the ".wikipage em" "css_element"

    Examples:
      | format | input                                            |
      | HTML   | <strong>Forcing</strong> the <em>use of</em> the |
      | Creole | **Forcing** the //use of// the                   |
      | NWiki  | ''Forcing'' the '''use of''' the                 |