<div class='wrap'><?php print coia_donate_html(); ?>
  <h2>Cookie-Opt-In Information for Developers</h2>
  <div id="cookie">
    <p>To adapt your code to respect the wishes of the visitor, do one of the following:</p>
    <p>(Remember; you need to do this only if your scripts or code uses cookies which are not a must-have for the site to work! See <a href="<?php print admin_url('admin.php?page=cookie-opt-in'); ?>">'Info'</a>).</p>
    <ol>
      <li>Make the inclusion or printing of script conditional. You can use either the short-cut function <code>coia</code> or the WordPress <code>filter</code> approach;</li>
      <li><code>&lt;?php if (!function_exists('coia') || coia('tracking')) {
  // include google analytics
}?&gt;</code></li>
      <li><code>&lt;?php if (apply_filters('eu_cookie_consent', 'tracking') != 'denied') {
  // do your thing here
  //
}?&gt;</code>
      </li>
    </ol>
    <p>Allowed keywords are 'tracking', 'advertisement', 'social' and 'functional', however, conditionalising 'functional' is not useful because these cookies are never blocked.</p>
    <p>(coia stands for cookie-opt-in-accepted)</p>
    <p>Alternatively, you can mail us the add_action-calls that call the code that places the cookies, we'll include this in the next release. (also see <a href="<?php print admin_url('admin.php?page=cookie-opt-in-actions'); ?>">Actions overview</a>)</p>
    <p>For the following types of cookies you MUST have permission:</p>
    <ol>
      <li>'tracking'; Scripts and code from statistics software that use cookies to keep track of the users actions, like Google Analytics</li>
      <li>'advertisement'; Scripts and code from advertisement companies that use cookies to determine what ads to show, like Google Adwords</li>
      <li>'social'; Scripts and code from third parties that use cookies to keep track of your social activities, like Facebook and Google+</li>
    </ol>
    <p>For the following types of cookies you do not need permission but you'll have to INFORM them:</p>
    <ol>
      <li>'functional'; A general type of cookie which is used for cookies that are necessary for the proper functioning of the site, like cookies that keep the state of the web-application</li>
    </ol>
    <p>Again, this plugin will inform the visitor on the 'functional' cookies, but will not provide a way to deny them; they are required for your site and without these cookies your site cannot function.</p>
    <h2>Notes;</h2>
    <ol>
      <li>A default WordPress site does NOT use cookies in the front-end, only in the back-end (CMS). Because the CMS is not a public part, the law doesn't apply. For a standard WordPress site you DON'T need premission.</li>
      <li>Laws change every day and although we do our best to keep ourselves informed, we might miss something. We're only human. Please contact us if you encounter something that is not right!</li>
    </ol>
    <h2>Need help?</h2>
    <p>We can help you! If you need help adapting your code, send us an e-mail <a href="mailto:support@clearsite.nl">support@clearsite.nl</a>. (Please note: no promisses on response times, but we'll try our best.)</p>
  </div>
  <h2>Interface alterations.</h2>
  <p>You can block the plugin stylesheet and interface javascript by the following code;</p>
  <code>
    add_filter('do_not_load_cookie_opt_in_visual_effects', 'my_plugin_of_theme_namespace_no_coia_visuals');
    function my_plugin_of_theme_namespace_no_coia_visuals() {
      return true;
    }
  </code>
  <p>After that you can completely style the interface yourself and/or use jQuery to alter the DOM for it.</p>
  <p>In your javascript you can define methods on the cookie_opt_in object to hook in the various processes;</p>
  <p>Example:</p>
  <code>
    cookie_opt_in.hide_after = function () {
      // an action that happens after the interface is hidden.
    };
  </code>
  <p>The following 'hooks' are available:</p>
  <table>
    <tr>
      <th>init_before, init_after</th><td>Triggered before/after the interface elements are created</td>
    </tr>
    <tr>
      <th>activate_before, activate_after</th><td>Triggered before/after events are attached to the interface elements (like bind('click') )</td>
    </tr>
    <tr>
      <th>action_before, action_after (*1)</th><td>Triggered before/after a button is clicked (must be an <code>input</code> with class <code>button</code>)</td>
    </tr>
    <tr>
      <th>show_before, show_after (*2)</th><td>Triggered before/after the interface is shown</td>
    </tr>
    <tr>
      <th>hide_before, hide_after</th><td>Triggered before/after the interface is hidden</td>
    </tr>
  </table>
  <p>Notes;</p>
  <ol>
    <li>Method receives <code>this</code> as parameter</li>
    <li>Method receives <code>boolean</code> as parameter, true indicating the cookie is new (first time visit)</li>
  </ol>
</div>
