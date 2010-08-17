Welcome to the Avalon PHP framework.
<p>
	This page is controlled by the Welcome controller, located in the <code>app/controllers/</code> directory.
</p>
<p>
	To edit the content of this page, you can edit the <code>welcome.php</code> file in the <code>app/views/</code> directory.
</p>
<p>
	All views are wrapped with a layout, to change the layout, edit the <code>default.php</code> file in the <code>app/views/_layouts/</code> directory.
</p>
<h2>Routing</h2>
You can add and modify routes to controllers in the <code>routes.php</code> file in the <code>app/config/</code> directory.
<p>
	If you want to setup a route to the <code>HelloWorld</code> method in the <code>Hello</code> controller, open the routes config file and add the follwing line:
	<pre>$routes['hello-world'] = 'Hello/HelloWorld';</pre>
	This will allow you to access the method via <?php echo $html->link($this->uri->anchor('hello-world'),'/hello-world'); ?></a>
</p>