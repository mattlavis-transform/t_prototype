<?php
class back_to_top_control
{
    // Class properties and methods go here

    public function __construct()
    {
        $this->display();
    }

    private function display()
    {

?>
        <div class="app-back-to-top" data-module="app-back-to-top">
            <a class="govuk-link govuk-link--no-visited-state app-back-to-top__link" href="#top">
                <svg role="presentation" focusable="false" class="app-back-to-top__icon" xmlns="http://www.w3.org/2000/svg" width="13" height="17" viewBox="0 0 13 17">
                    <path fill="currentColor" d="M6.5 0L0 6.5 1.4 8l4-4v12.7h2V4l4.3 4L13 6.4z"></path>
                </svg>Back to top
            </a>
        </div>
<?php
    }
}
?>