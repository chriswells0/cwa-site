				<div class="content">
					<h1>Home Page</h1>
					<div class="content-body">
						<p>To update this page, <a href="/account/login">log in</a> as an admin or developer and edit <a href="/admin/code/views~Site~index.html.php">index.html.php</a>.</p>
						<p><a title="View the CWA Site project" href="https://github.com/chriswells0/cwa-site" rel="external">Core Web Application Site</a> was created to help developers quickly launch new sites leveraging the <a href="https://github.com/chriswells0/cwa-lib" title="View the CWA Libraries project" rel="external">Core Web Application Libraries</a>. It's designed to be easily customized and extended.</p>
						<p>Be sure to visit the <a title="Administer this site" href="../../admin">Site Admin</a> section to familiarize yourself with the built-in tools. Although it's not on the main menu, there's also a dynamic <a title="View the site map" href="../../site/map">site map</a> that you can use as a reference for the main URLs.</p>
						<h2>Getting Started</h2>
						<p>These steps should be performed right away:</p>
						<ol>
							<li><a title="Log into this site" href="../../account/login">Log in</a> as <span class="label">admin</span> using the default password: <span class="label">wku(&lt;o%x=%-9</span></li>
							<li><a title="Administer users" href="../../users/admin">Update the default users</a> to have correct contact information and passwords you can remember.</li>
							<li>If you haven't already, create your <a title="Create an Analytics account" href="http://www.google.com/analytics/" rel="external">Google Analytics</a> and <a title="Create a reCAPTCHA account" href="https://www.google.com/recaptcha/admin/create" rel="external">reCAPTCHA</a> accounts.</li>
							<li>Update <a title="Edit config.php" href="../../admin/code/config~config.php">/application/config/config.php</a> to include your details in the following variables:
								<ul>
									<li>All of the app-specific constants at the top.</li>
									<li><span class="label">RECAPTCHA_PUBLIC_KEY</span> and <span class="label">RECAPTCHA_PRIVATE_KEY</span></li>
									<li><span class="label">ANALYTICS_ID</span> (leave commented/undefined in the non-production section)</li>
								</ul>
							</li>
							<li>Edit this page to get started on your site!</li>
						</ol>
						<h2>Customizing The Design</h2>
						<p>These are the primary files to edit in order to redesign the site:</p>
						<ol>
							<li><a href="../../admin/code/views~_layouts~default.html.php">/application/views/_layouts/default.html.php</a> provides the default page layout.</li>
							<li><a href="../../admin/code/views~_shared">/application/views/_shared/</a> contains many of the files included in <span class="label">default.html.php</span>.</li>
							<li><span class="label">/public/</span> contains static files such as the main CSS file at <span class="label">/public/styles/main.css</span>.</li>
							<li>Be sure to replace <span class="label">/public/images/logo.png</span> and <span class="label">/public/favicon.ico</span> with your own images.</li>
							<li>Role-based permissions are set in <a href="../../admin/code/main.php">/application/main.php</a>.</li>
						</ol>
						<p>Once you've customized the site, it's recommended that you delete the main <span class="label">.gitignore</span> and <span class="label">.gitmodules</span> files as well as the <span class="label">.git</span> directory. Then update the <a href="https://github.com/chriswells0/cwa-lib" title="View the CWA Libraries project" rel="external">Core Web Application Libraries</a> regularly using git inside the <span class="label">/lib/cwa</span> directory.</p>
					</div>
				</div>
