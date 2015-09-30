@mod @mod_socialwiki
Feature: Using different format options
  In order to use a specific format
  As a user
  I need to select what format to use

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email |
      | teacher1 | Teacher | 1 | teacher1@example.com |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1 | 0 |
    And the following "course enrolments" exist:
      | user | course | role |
      | teacher1 | C1 | editingteacher |
    And I log in as "teacher1"
    And I follow "Course 1"
    And I turn editing mode on

  @javascript
  Scenario: Different formats
    And I add a "Social Wiki" to section "1" and I fill the form with:
      | Social Wiki name | General Socialwiki |
      | Description | A normal test of the socialwiki. |
      | First page name | Test Page |
    And I follow "HTML Socialwiki"
    When I set the following fields to these values:
      | HTML format | 1 |
    And I press "Create page"
    And I set the following fields to these values:
      | HTML format | This is the first page in the wiki, with the HTML format |
    And I press "Save"
    And I should see "This is the first page in the wiki, with the HTML format"
    And I follow "HTML Socialwiki"
    And I follow "Pages"
    And I press "Make a new Page"
    When I set the following fields to these values:
      | Creole format | 1 |
    And I press "Create page"
    Then "div.socialwikieditor-toolbar" "css_element" should exist
    # Click on bold, italic, interal link and H1
    And I click on "//div[@class='socialwikieditor-toolbar']/descendant::a[1]" "xpath_element"
    And I click on "//div[@class='socialwikieditor-toolbar']/descendant::a[2]" "xpath_element"
    And I click on "//div[@class='socialwikieditor-toolbar']/descendant::a[4]" "xpath_element"
    And the field "newcontent" matches value "**Bold text**//Italic text//[[Internal link]]"
    And I click on "//div[@class='socialwikieditor-toolbar']/descendant::a[8]" "xpath_element"
    And I press "Save"
    And I should see "Bold textItalic textInternal link"
    And I should see "Level 1 Header"
    And I should see "Table of contents"
    And I click on "Level 1 Header" "link" in the ".socialwiki-toc" "css_element"
    And I follow "Internal link"
    When I set the following fields to these values:
      | New page title | NWiki page |
      | NWiki format | 1 |
    And I press "Create page"
    Then "div.socialwikieditor-toolbar" "css_element" should exist
    # Click on italic, interal link and H1
    And I click on "//div[@class='socialwikieditor-toolbar']/descendant::a[2]" "xpath_element"
    And I click on "//div[@class='socialwikieditor-toolbar']/descendant::a[4]" "xpath_element"
    And the field "newcontent" matches value "'''Italic text'''[[Internal link]]"
    And I click on "//div[@class='socialwikieditor-toolbar']/descendant::a[8]" "xpath_element"
    And I press "Save"
    And I should see "Italic textInternal link"
    And I should see "Level 1 Header"
    And I should see "Table of contents"
    And I click on "Level 1 Header" "link" in the ".socialwiki-toc" "css_element"
    And I follow "Internal link"
    And I should see "New page title"

  @javascript
  Scenario: Forced format
    And I add a "Social Wiki" to section "1" and I fill the form with:
      | Social Wiki name | ForceFormat Socialwiki |
      | Description | A test of the socialwiki where the editing format is forced to HTML. |
      | First page name | Test Page |
      | Default format | Creole |
      | Force format | 1 |
    And I follow "ForceFormat Socialwiki"
    And I set the following fields to these values:
      | Creole format | This is the first page in the wiki, with the forced Creole format |
    And I press "Save"