<?php

// This is a super global setup that changes the default factory for QuickForm renderers.
// It is NOT part of Moodle code standards and should not be reintegrated into upper files.

$GLOBALS['_HTML_QuickForm_default_renderer'] = new MoodleQuickForm_Tabbed_Renderer();
