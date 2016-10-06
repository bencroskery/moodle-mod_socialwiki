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
      | user     | course | role    |
      | teacher1 | C1     | editingteacher |
    And I log in as "teacher1"
    And I follow "Course 1"
    And I turn editing mode on

  @javascript
  Scenario Outline: Wikieditor
    When I add a "Social Wiki" to section "1" and I fill the form with:
      | Social Wiki name | Test Socialwiki |
    And I follow "Test Socialwiki"
    And I follow "Pages"
    And I press "id_submitbutton"
    And I set the field "New page title" to "<format> Page"
    And I set the field "<format> format" to "1"
    And I press "Create page"
    # Use toolbar buttons
    Then ".socialwikieditor-toolbar" "css_element" should be visible
    When I click on "Bold text" "button"
    And I click on "Italic text" "button"
    And I click on "Unordered list" "button"
    And I click on "Ordered list" "button"

    Examples:
      | format |
      | Creole |
      | NWiki  |

  Scenario Outline: Forced format + Nojs
    And I add a "Social Wiki" to section "1" and I fill the form with:
      | Social Wiki name | Force Format Socialwiki |
      | Default format   | <format>                |
      | Force format     | 1                       |
    And I follow "Force Format Socialwiki"
    And I follow "Pages"
    And I press "Make a new Page"
    And I should not see "Format" in the "#socialwiki_content_area" "css_element"
    And I set the field "New page title" to "Forced <format> page"
    And I press "Create page"
    And I set the field "<format> format" to "<input> [[<format> format]]."
    And I press "Save"
    Then I should see "Forcing" in the ".wikipage strong" "css_element"
    And I should see "use of" in the ".wikipage em" "css_element"

    Examples:
      | format | input                                            |
      | HTML   | <strong>Forcing</strong> the <em>use of</em> the |
      | Creole | **Forcing** the //use of// the                   |
      | NWiki  | ''Forcing'' the '''use of''' the                 |