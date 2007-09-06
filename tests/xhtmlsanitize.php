<?php
    global $libs;
    $libs->Load( 'sanitizer' );

    final class TestXHTMLSanitizer extends Testcase {
        public function TestClassesExist() {
            $this->Assert( class_exists( 'XHTMLSanitizer' ) );
            $this->Assert( class_exists( 'XHTMLSaneTag' ) );
            $this->Assert( class_exists( 'XHTMLSaneAttribute' ) );
        }
        public function TestMethodsExist() {
            $sanitizer = New XHTMLSanitizer();
            $this->Assert( method_exists( $sanitizer, 'SetSource' ) );
            $this->Assert( method_exists( $sanitizer, 'Sanitize' ) );
            $this->Assert( method_exists( $sanitizer, 'GetXHTML' ) );
            $this->Assert( method_exists( $sanitizer, 'AllowTag' ) );
            $tag = New XHTMLSaneTag( 'b' );
            $this->Assert( method_exists( $tag, 'AllowAttribute' ) );
            $attribute = New XHTMLSaneAttribute( 'class' );
        }
        public function TestSimple() {
            $sanitizer = New XHTMLSanitizer();
            $sanitizer->SetSource( '' );
            $sanitizer->Sanitize();
            $this->AssertEquals( '', $sanitizer->GetXHTML(), 'The empty string should be remain unchanged' );
            $sanitizer->SetSource( 'Hello, world!' );
            $sanitizer->Sanitize();
            $this->AssertEquals( 'Hello, world!', $sanitizer->GetXHTML(), 'Failed to sanitize a simple string' );
            $sanitizer->SetSource( 'While class Bar offers a generalized behavior in function Go, it allows specialization, if the derived class desires, by making its Run function virtual. Notice that the proper function is invoked because the instantiation is done as Bar, not as Foo, even though the Go function is defined within Foo.' );
            $sanitizer->Sanitize();
            $this->AssertEquals( 'While class Bar offers a generalized behavior in function Go, it allows specialization, if the derived class desires, by making its Run function virtual. Notice that the proper function is invoked because the instantiation is done as Bar, not as Foo, even though the Go function is defined within Foo.', $sanitizer->GetXHTML(), 'Failed to sanitize a simple, longer, string' );
        }
        public function TestWrongTypes() {
            $sanitizer = New XHTMLSanitizer();
            $sanitizer->SetSource( 5 );
            $sanitizer->Sanitize();
            $this->AssertEquals( '5', $sanitizer->GetXHTML(), 'The sanitizer should convert integer source to string, and raise appropriate notices' );
            $sanitizer->SetSource( false );
            $sanitizer->Sanitize();
            $this->AssertEquals( '', $sanitizer->GetXHTML(), 'The sanitizer should convert boolean false source to string, and raise appropriate notices' );
            $sanitizer->SetSource( true );
            $sanitizer->Sanitize();
            $this->AssertEquals( '1', $sanitizer->GetXHTML(), 'The sanitizer should convert boolean true source to string, and raise appropriate notices' );
            $sanitizer->SetSource( array() );
            $sanitizer->Sanitize();
            $this->AssertEquals( '', $sanitizer->GetXHTML(), 'The sanitizer should discard non-scalar sources, and raise appropriate warnings' );
            $sanitizer->SetSource( $this );
            $sanitizer->Sanitize();
            $this->AssertEquals( '', $sanitizer->GetXHTML(), 'The sanitizer should discard non-scalar sources, and raise appropriate warnings' );
        }
        public function TestEntities() {
            // http://www.w3schools.com/tags/ref_entities.asp
            
            $sanitizer = New XHTMLSanitizer();
            $sanitizer->SetSource( 'Hello &amp; world!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello &amp; world!', $result, 'Valid entities should remain unchanged (&amp;)' );
            $sanitizer->SetSource( 'Hello & world!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello &amp; world!', $result, 'Invalid entities should be escaped to produce valid XHTML (&)' );
            $sanitizer->SetSource( 'Hello &amp world!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello &amp;amp world!', $result, 'Invalid entities should be escaped to produce valid XHTML (&amp)' );
            $sanitizer->SetSource( '&Ograve;' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&Ograve;', $result, 'Valid entities should remain unchanged (&Ograve;)' );
            $sanitizer->SetSource( 'haha&Iacute;&OElig;&spades;heh&thetasym;&cent;&#8217;&#X03bb;&#x03BB;&#955;hoho' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'haha&Iacute;&OElig;&spades;heh&thetasym;&cent;&#8217;&#X03bb;&#x03BB;&#955;hoho', $result, 'Valid entities should remain unchanged (multiple)' );
            $sanitizer->SetSource( '&;' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&amp;;', $result, 'Invalid entities should be escaped to produce valid XHTML (&;)' );
            $sanitizer->SetSource( '&#18237910392839;' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&amp;#18237910392839;', $result, 'Invalid entities should be escaped to produce valid XHTML (&#18237910392839;)' );
            $sanitizer->SetSource( '&#x112111211;' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&amp;#x112111211;', $result, 'Invalid entities should be escaped to produce valid XHTML (&#x112111211;)' );
            $sanitizer->SetSource( '&#x1121112111;' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&amp;#x1121112111;', $result, 'Invalid entities should be escaped to produce valid XHTML (&#x112111211;)' );
            $sanitizer->SetSource( '&#xtata;' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&amp;#xtata;', $result, 'Invalid entities should be escaped to produce valid XHTML (&#xtata;)' );
            $sanitizer->SetSource( '&#Xtata;' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&amp;#Xtata;', $result, 'Invalid entities should be escaped to produce valid XHTML (&#Xtata;)' );
            $sanitizer->SetSource( '&#x121;' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&amp;#x121;', $result, 'Odd length should not be allowed for hexadecimal entities (&#x121;)' );
            $sanitizer->SetSource( '&#xf00d;' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&#xf00d;', $result, 'Valid entities should remain unchanged (&#xf00d;)' );
            $sanitizer->SetSource( '&#Xf00d;' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&#Xf00d;', $result, 'Valid entities should remain unchanged (&#Xf00d;)' );
            $sanitizer->SetSource( '&#xF00D;' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&#xF00D;', $result, 'Valid entities should remain unchanged (&#xF00D;)' );
            $sanitizer->SetSource( '&#XF00D;' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&#XF00D;', $result, 'Valid entities should remain unchanged (&#XF00D;)' );
            $sanitizer->SetSource( '&apos;&quot;&gt;&lt;' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&#XF00D;', $result, 'Valid entities should remain unchanged (&#XF00D;)' );
        }
        public function TestWhitespace() {
            $sanitizer = New XHTMLSanitizer();
            $sanitizer->SetSource( '                       ' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( ' ', $result, 'Multiple spaces should be consumed to a single space' );
            $sanitizer->SetSource( "\n\n\n\n" );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( ' ', $result, 'Multiple new lines should be consumed to a single space' );
            $sanitizer->SetSource( "\n  \n          \n\n      " );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( ' ', $result, 'Multiple new lines and spaces should be consumed to a single space' );
            $sanitizer->SetSource( "\n\r\n\r\n\r\r\r \r\n\n\n \t\t    \t\n\r" );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( ' ', $result, 'Multiple whitespace characters should be consumed to a single space' );
            $sanitizer->SetSource( "hahaha          haha\n\n\n\n\n\nhoho" );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'hahaha haha hoho', $result, 'Whitespace should be replaced with a single space' );
        }
        public function TestComments() {
            $sanitizer = New XHTMLSanitizer();
            $sanitizer->SetSource( '<!-- bwahahh -->' );
            $this->AssertEquals( '', $sanitizer->GetXHTML(), 'HTML comments should be removed' );
        }
        public function TestNoTags() {
            $sanitizer = New XHTMLSanitizer();
            $sanitizer->SetSource( 'Hello <b>world</b>!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello world!', $result, 'Undefined tags should be removed' );
            $sanitizer->SetSource( 'Hello <em><b>world</b></em>!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello world!', $result, 'Undefined tags should be removed' );
            $sanitizer->SetSource( '<div>Hello <em><b>world</b></em>!</div>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello world!', $result, 'Undefined tags should be removed' );
            $sanitizer->SetSource( '<div>Hello   <em> <b> world </b> </em> ! </div>!!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello world ! !!', $result, 'Undefined tags should be removed' );
        }
        public function TestSimpleTag() {
            $sanitizer = New XHTMLSanitizer();
            $sanitizer->AllowTag( New XHTMLSaneTag( 'b' ) );
            $sanitizer->SetSource( '<b></b>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<b></b>', $result, 'Simple empty tag should remain unchanged' );
            $sanitizer->SetSource( '<b />' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&lt;b /&gt;', $result, 'Tags with mandatory content should be explicitly closed by the user' );
            $sanitizer->SetSource( 'Hello <b>world</b>!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello <b>world</b>!', $result, 'Simple use of a defined tag should be allowed' );
            $sanitizer->SetSource( '<b>Hello</b> <b>world</b>!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<b>Hello</b> <b>world</b>!', $result, 'Simple use of two defined tag should be allowed' );
            $sanitizer->SetSource( '<b>Hello </b><b>world</b>!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<b>Hello </b><b>world</b>!', $result, 'Simple use of whitespace within defined tags should be allowed' );
        }
        public function TestCapitalizedTags() {
            $sanitizer = New XHTMLSanitizer();
            $sanitizer->AllowTag( New XHTMLSaneTag( 'b' ) );
            $sanitizer->AllowTag( New XHTMLSaneTag( 'span' ) );
            $sanitizer->SetSource( '<B></B>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<b></b>', $result, 'Capitalized tags should be converted to lower-case' );
            $sanitizer->SetSource( '<sPAn></sPaN>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<span></span>', $result, 'Various caps tags should be converted to lower-case; matching closing tags should not require the same capitalization' );
        }
        public function TestUnclosedTags() {
            $sanitizer = New XHTMLSanitizer();
            $sanitizer->AllowTag( New XHTMLSaneTag( 'b' ) );
            $sanitizer->SetSource( 'Hello <b>world!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello <b>world!</b>', $result, 'Unclosed tags should auto-close' );
            $sanitizer->SetSource( 'Hello <em>world!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello world!', $result, 'Disallowed tags should not auto-close' );
            $sanitizer->SetSource( 'Hello <b><em><b>world!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello <b><b>world!</b></b>', $result, 'Allowed tags should auto-close when nested and contain disallowed tags' );
            $sanitizer->SetSource( 'Hello <b><b>world</b>!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello <b><b>world</b>!</b>', $result, 'Allowed tags should auto-close when nested' );
            
            $sanitizer->AllowTag( New XHTMLSaneTag( 'br' ) );
            $sanitizer->SetSource( '<br/>' );
            $this->AssertEquals( '<br />', $result, 'Short closing should include a space before the short-close slash' );
            $sanitizer->SetSource( '<br>' );
            $this->AssertEquals( '<br />', $result, 'Unclosed tags should be automatically short-closed if contentless (br, img, etc.)' );
        }
        public function TestTwoTags() {
            $sanitizer = New XHTMLSanitizer();
            $sanitizer->AllowTag( New XHTMLSaneTag( 'strong' ) );
            $sanitizer->AllowTag( New XHTMLSaneTag( 'em' ) );
            $sanitizer->SetSource( 'Hello <strong><em>world</em></strong>!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello <strong><em>world</em></strong>!', $result, 'Two allowed tags should not be filtered' );
            $sanitizer->SetSource( 'Hello <strong><em>world!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello <strong><em>world!</em></strong>', $result, 'Two allowed tags should auto-close' );
            $sanitizer->SetSource( 'Hello <strong><em>world</em>!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello <strong><em>world</em>!</strong>', $result, 'Allowed tag should auto-close when other allowed tag exists within it' );
            $sanitizer->SetSource( 'Hello <strong><em>world!</strong>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello <strong><em>world!</em></strong>', $result, 'Auto-close should observe nesting rules' );
            $sanitizer->SetSource( 'Hello <strong><em>world</strong>!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello <strong><em>world</em></strong>!', $result, 'Auto-close should observe nesting rules' );
            $sanitizer->SetSource( 'Hello <strong>world</strong>!</em>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello <strong>world</strong>!&lt;/em&gt;', $result, 'Closed tags that did not open should be escaped' );
            $sanitizer->SetSource( '<em>Hello</em> <strong>world</strong>!</em>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<em>Hello</em> <strong>world</strong>!&lt;/em&gt;', $result, 'Closed tags that did not open should be escaped' );
            $sanitizer->SetSource( '<em><em>Hello</em> <strong>world</strong>!</em>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<em><em>Hello</em></em> <strong>world</strong>!&lt;/em&gt;', $result, 'Closed tags that did not open should be escaped, even when auto-closing occurred' );
            $sanitizer->SetSource( '<em><em>Hello <strong>world</strong>!</em>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<em><em>Hello <strong>world</strong>!</em></em>', $result, 'Auto-closing should not be affected by interfering tags' );
        }
        public function TestTagsWithEntities() {
            $sanitizer = New XHTMLSanitizer();
            $sanitizer->AllowTag( New XHTMLSaneTag( 'strong' ) );
            $sanitizer->AllowTag( New XHTMLSaneTag( 'em' ) );
            $sanitizer->SetSource( '<em><em>Hello &amp;<strong>world</strong>!</em></em>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<em><em>Hello &amp;<strong>world</strong>!</em></em>', $result, 'Allowed tags and allowed entities should remain unchanged' );
            $sanitizer->SetSource( '&lt;em&gt;Hello&lt;/em&gt;' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&lt;em&gt;Hello&lt;/em&gt;', $result, 'Allowed entities should remain unchanged, even when they look like tags' );
            $sanitizer->SetSource( '&lt;em&gt;Hello</em>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&lt;em&gt;Hello&lt/em&gt;', $result, 'Allowed entities should remain unchanged while closed tags that did not open are escaped' );
            $sanitizer->SetSource( '<em>Hello&lt;/em&gt;' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<em>Hello&lt;/em&gt;</em>', $result, 'Auto-closing should remain unaffected from entities' );
        }
        public function TestInvalidTags() {
            // http://www.w3schools.com/tags/default.asp
            
            $sanitizer = New XHTMLSanitizer();
            $sanitizer->AllowTag( New XHTMLSaneTag( 'koko' ) );
            $sanitizer->AllowTag( New XHTMLSaneTag( 'lala' ) );
            $sanitizer->SetSource( '<koko><lala /> liruliru</koko>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&lt;koko&gt;&lt;lala /&gt; liruliru&lt;koko&gt;', $result, 'Invalid tags (koko, lala, and so forth) should not be allowed even if explicitly specified; appropriate warnings should be raised' );
            
            $sanitizer->AllowTag( New XHTMLSaneTag( 'dir' ) );
            $sanitizer->AllowTag( New XHTMLSaneTag( 'applet' ) );
            $sanitizer->AllowTag( New XHTMLSaneTag( 'isindex' ) );
            $sanitizer->SetSource( '<dir>haha<applet>yes</applet></dir>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'hahayes', $result, 'Deprecated tags (dir, applet, isindex, and so forth) should not be allowed even if explicitly specified; appropriate warnings should be raised, distinguishing them from invalid tags' );
            
            $sanitizer->AllowTag( New XHTMLSaneTag( 'b' ) );
            $sanitizer->AllowTag( New XHTMLSaneTag( 'script' ) );
            $sanitizer->SetSource( '<b>haha</b><script>alert("XSS!");</script>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<b>haha</b>alert("XSS!");', $result, 'Insecure tags (script, link, style) should be disallowed even if explicitly specified; appropriate warnings should be raised' );
        }
        public function TestBlocks() {
            // http://en.wikipedia.org/wiki/HTML_element#Block
            
            $sanitizer = New XHTMLSanitizer();
            $sanitizer->AllowTag( New XHTMLSaneTag( 'div' ) );
            $sanitizer->AllowTag( New XHTMLSaneTag( 'span' ) );
            $sanitizer->SetSource( '<span></span>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<span></span>', $result, 'Allowed tags should remain at place even when empty' );
            $sanitizer->SetSource( '<div><span></span></div>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<div><span></span></div>', $result, 'Span within div is allowed' );
            $sanitizer->SetSource( '<span><div></div></span>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<span></span>', $result, 'Div within spam is not allowed (block in inline)' );
            $sanitizer->SetSource( '<div>Hello <span>my</span> friend</div>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<div>Hello my friend</div>', $result, 'Span within div consumption should preserve content' );
            
            $sanitizer->AllowTag( New XHTMLSaneTag( 'form' ) );
            $sanitizer->SetSource( '<span><form>Ye<span>aa</span>ah</form></span>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<span>Ye<span>aa</span>ah</span>', $result, 'Block elements should not be allowed within inline; content should be preserved' );
        }
        public function TestNoAttributes() {
            $sanitizer = New XHTMLSanitizer();
            $sanitizer->AllowTag( New XHTMLSaneTag( 'span' ) );
            $sanitizer->SetSource( '<span class="standing">by the way</span>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<span>by the way</span>', $result, 'Attributes not explicitly declared within allowed tags should be eliminated' );
            $sanitizer->SetSource( '<div class="standing">by the way</div>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'by the way', $result, 'Undeclared tags should be removed even when containing attributes' );
            $sanitizer->SetSource( '<div class=standing">by the way</div>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'by the way', $result, 'Undeclared tags should be removed even when containing attributes with invalid quotation' );
            $sanitizer->SetSource( '<div class=standing>by the way</div>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'by the way', $result, 'Undeclared tags should be removed even when containing attributes without quotation' );
            $sanitizer->SetSource( '<div class>by the way</div>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'by the way', $result, 'Undeclared tags should be removed even when containing attributes with no values' );
            $sanitizer->SetSource( '<div class=>by the way</div>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'by the way', $result, 'Undeclared tags should be removed even when containing attributes with no values that include the equals sign' );
        }
        public function TestSimpleAttributes() {
            $span = New XHTMLSaneTag( 'span' );
            $span->AllowAttribute( New XHTMLSaneAttribute( 'class' ) );
            $sanitizer = New XHTMLSanitizer();
            $sanitizer->AllowTag( $span );
            $sanitizer->SetSource( '<span class="standing">by the way</span>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<span class="standing">by the way</span>', $result, 'Attributes explicitly declared within allowed tags should remain unchanged' );
            $sanitizer->SetSource( '<span class="">by the way</span>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<span class="">by the way</span>', $result, 'Allowed attributes should remain unchanged even when empty' );
            $sanitizer->SetSource( '<span class="" title="">by the way</span>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<span class="">by the way</span>', $result, 'Allowed attributes should remain unchanged even when empty' );
            $sanitizer->SetSource( '<span class="two classes">by the way</span>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<span class="two classes">by the way</span>', $result, 'Spaces should be preserved within attribute values' );
            $sanitizer->SetSource( '<span class=\'standing\'>by the way</span>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<span class="standing">by the way</span>', $result, 'Attribute values included in single quotation marks should be converted to double quotation marks' );
            $sanitizer->SetSource( '<span class=\'Hello "world" of sorrow\'>by the way</span>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<span class="Hello &quot;world&quot; of sorrow">by the way</span>', $result, 'Attribute values included in single quotation marks should be converted to double quotation marks with respect to double quotation marks' );
        }
        public function TestAttributesWhitespace() {
            $span = New XHTMLSaneTag( 'span' );
            $span->AllowAttribute( New XHTMLSaneAttribute( 'class' ) );
            $sanitizer = New XHTMLSanitizer();
            $sanitizer->AllowTag( $span );
            $sanitizer->SetSource( '<span class="two     classes">by the way</span>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<span class="two classes">by the way</span>', $result, 'Whitespace within attributes should be consumed to a single space' );
            $sanitizer->SetSource( "<span class=\"two\n\n\n\t\t  classes\">by the way</span>" );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<span class="two classes">by the way</span>', $result, 'Whitespace within attributes should be consumed to a single space (tabs, newlines, and spaces)' );
            $sanitizer->SetSource( "<span class=\"two\n\n\n\r\t\tclasses\">by the way</span>" );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<span class="two classes">by the way</span>', $result, 'Whitespace within attributes should be consumed to a single space (newlines and tabs)' );
        }
        public function TestAttributesWithEntities() {
            $span = New XHTMLSaneTag( 'span' );
            $span->AllowAttribute( New XHTMLSaneAttribute( 'class' ) );
            $sanitizer = New XHTMLSanitizer();
            $sanitizer->AllowTag( $span );
            $sanitizer->SetSource( '<span class="stand&lt;ing">by the way</span>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<span class="stand&lt;ing">by the way</span>', $result, 'Valid attribute values containing entities should be preserved' );
            $sanitizer->SetSource( '<span class="&gt;&lt;">by the way</span>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<span class="&gt;&lt;">by the way</span>', $result, 'Valid attribute values consisting solely of entities should be preserved' );
            $sanitizer->SetSource( '<span class="&">by the way</span>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<span class="&amp;">by the way</span>', $result, 'Invalid entities within attribute values should be escaped (&)' );
            $sanitizer->SetSource( '<span class="&amp">by the way</span>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<span class="&amp;amp">by the way</span>', $result, 'Invalid entities within attribute values should be escaped (&amp)' );
            $sanitizer->SetSource( '<span class="<<>>">by the way</span>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<span class="&lt;&lt;&gt;&gt;">by the way</span>', $result, 'Invalid entities within attribute values should be escaped; attribute ending should be indicated by the closing quotation marks' );
        }
        public function TestCapitalizedAttributes() {
            $sanitizer = New XHTMLSanitizer();
            $span = New XHTMLSaneTag( 'span' );
            $span->AllowAttribute( New XHTMLSaneAttribute( 'class' ) );
            $sanitizer->AllowTag( $span );
            $sanitizer->SetSource( '<span CLASS="standing">by the way</span>' );
            $this->AssertEquals( '<span class="standing">by the way</span>', $sanitizer->GetXHTML(), 'Capitalized attributes should be converted to lower-case' );
            $sanitizer->SetSource( '<span cLaSs="standing">by the way</span>' );
            $this->AssertEquals( '<span class="standing">by the way</span>', $sanitizer->GetXHTML(), 'Various caps attributes should be converted to lower-case' );
            $sanitizer->SetSource( '<span class="STANDING">by the way</span>' );
            $this->AssertEquals( '<span class="STANDING">by the way</span>', $sanitizer->GetXHTML(), 'Capitalized attribute values should remain unchanged' );
            $sanitizer->SetSource( '<span class="StANdIng">by the way</span>' );
            $this->AssertEquals( '<span class="StANdIng">by the way</span>', $sanitizer->GetXHTML(), 'Various caps attribute values should remain unchanged' );
        }
        public function TestInvalidAttributes() {
            $span = New XHTMLSaneTag( 'span' );
            $sanitizer = New XHTMLSanitizer();
            $span->AllowAttribute( New XHTMLSaneAttribute( 'class' ) );
            $span->AllowAttribute( New XHTMLSaneAttribute( 'title' ) );
            $sanitizer->AllowTag( $span );
            $sanitizer->SetSource( '<span class>by the way</span>' );
            $this->AssertEquals( '<span>by the way</span>', $sanitizer->GetXHTML(), 'Attributes with no values should be removed' );
            $sanitizer->SetSource( '<span class=>by the way</span>' );
            $this->AssertEquals( '<span>by the way</span>', $sanitizer->GetXHTML(), 'Attributes with no values should be removed even when equal sign is present' );
            $sanitizer->SetSource( '<span class=haha>by the way</span>' );
            $this->AssertEquals( '<span>by the way</span>', $sanitizer->GetXHTML(), 'Attributes with unquoted values should be removed' );
            $sanitizer->SetSource( '<span class=haha hoho>by the way</span>' );
            $this->AssertEquals( '<span>by the way</span>', $sanitizer->GetXHTML(), 'Attributes with unquoted values should be removed when other invalid attributes are present' );
            $sanitizer->SetSource( '<span class title="hehe">by the way</span>' );
            $this->AssertEquals( '<span title="hehe">by the way</span>', $sanitizer->GetXHTML(), 'Valid attributes should be preserved when attributes with no values are removed' );
            $sanitizer->SetSource( '<span class= title="hehe">by the way</span>' );
            $this->AssertEquals( '<span title="hehe">by the way</span>', $sanitizer->GetXHTML(), 'Valid attributes should be preserved when attributes with no values including the equals sign are removed' );
            $sanitizer->SetSource( '<span class=bwahah title="hehe">by the way</span>' );
            $this->AssertEquals( '<span title="hehe">by the way</span>', $sanitizer->GetXHTML(), 'Valid attributes should be preserved when attributes with unquotes values are removed' );
            $sanitizer->SetSource( '<span class=bwahah title="hehe>by the way</span>">aahahah</span>' );
            $this->AssertEquals( '<span title="hehe&gt;by the way&lt;/span&gt;">aahahah</span>', $sanitizer->GetXHTML(), 'All attribute values should be escaped when within double quotation marks' );
            $sanitizer->SetSource( '<span class="bwahah" class="omg">aahahah</span>' );
            $this->AssertEquals( '<span class="omg">aahahah</span>', $sanitizer->GetXHTML(), 'Only the last occurence of a repeated tag should be preserved; appropriate notices should be raised for previous occurences (two repetitions)' );
            $sanitizer->SetSource( '<span class="" class="bwahah" class="omg" title="" class="hello">aahahah</span>' );
            $this->AssertEquals( '<span title="" class="omg">aahahah</span>', $sanitizer->GetXHTML(), 'Only the last occurence of a repeated tag should be preserved; appropriate notices should be raised for previous occurences (five repetitions)' );
        }
        public function TestInsecureAttributes() {
            $sanitizer = New XHTMLSanitizer();
            $span = New XHTMLSaneTag( 'span' );
            $span->AllowAttribute( New XHTMLSaneAttribute( 'style' ) );
            $sanitizer->AllowTag( $span );
            // TODO: CSS validation?
            $sanitizer->SetSource( '<span style="background-color:blue">hehehe</span>' );
            $this->AssertEquals( '<span>hehehe</span>', $sanitizer->GetXHTML(), 'Insecure attributes (style etc.) should be disallowed, and appropriate warnings should be raised' );
            $sanitizer->SetSource( '<span style="background-color:expression(alert(\'XSS\'))">ho ho</span>' );
            $this->AssertEquals( '<span>ho ho</span>', $sanitizer->GetXHTML(), 'Insecure attributes (style etc.) should be disallowed, and appropriate warnings should be raised' );
        }
        public function TestMandatoryAttributes() {
            $sanitizer = New XHTMLSanitizer();
            $img = New XHTMLSaneTag( 'img' );
            $img->AllowAttribute( New XHTMLSaneAttribute( 'src' ) );
            $img->AllowAttribute( New XHTMLSaneAttribute( 'alt' ) );
            $sanitizer->AllowTag( $img );
            $sanitizer->SetSource( '<img src="/images/big_orange.png" />' );
            $this->AssertEquals( 
                '<img src="/images/big_orange.png" alt="" />',
                $sanitizer->GetXHTML(), 'Mandatory attributes (alt in img) should be filled-in when non-existent'
            );
            $sanitizer = New XHTMLSanitizer();
            $img = New XHTMLSaneTag( 'img' );
            $img->AllowAttribute( New XHTMLSaneAttribute( 'src' ) );
            $sanitizer->AllowTag( $img );
            $sanitizer->SetSource( '<img src="/images/big_orange.png" alt="Orange" />' ); // <img src="/images/big_orange.png" alt="Orange" /> --> <img src="/images/big_orange.png" /> ("alt" attribute is not allowed) --> <img src="/images/big_orange.png" alt="" /> ("alt" attribute is not present but mandatory)
            $this->AssertEquals(
                '<img src="/images/big_orange.png" alt="Orange" />',
                $sanitizer->GetXHTML(), 'Mandatory attributes (alt in img) should be filled-in even when disallowed'
            );
        }
        public function TestContentlessTags() {
            $sanitizer = New XHTMLSanitizer();
            $img = New XHTMLSaneTag( 'img' );
            $img->AllowAttribute( New XHTMLSaneAttribute( "alt" ) );
            $img->AllowAttribute( New XHTMLSaneAttribute( "title" ) );
            $img->AllowAttribute( New XHTMLSaneAttribute( "src" ) );
            $span = New XHTMLSaneTag( 'span' );
            $sanitizer->AllowTag( $img );
            $sanitizer->AllowTag( $span );
            $sanitizer->SetSource( '<img src="http://www.google.com/someimage.jpg" alt="haha" title="hehe" />' );
            $this->AssertEquals( '<img src="http://www.google.com/someimage.jpg" alt="haha" title="hehe" />', $sanitizer->GetXHTML(), 'Valid contentless tags should be preserved' );
            $sanitizer->SetSource( '<img src="http://www.google.com/someimage.jpg" alt="haha" title="hehe"></img>' );
            $this->AssertEquals( '<img src="http://www.google.com/someimage.jpg" alt="haha" title="hehe" />', $sanitizer->GetXHTML(), 'Verbosely closed contentless tags should be converted to short-closed tags and preserved' );
            $sanitizer->SetSource( '<img src="http://www.google.com/someimage.jpg" alt="haha" title="hehe">bwahahah</img>' );
            $this->AssertEquals( '<img src="http://www.google.com/someimage.jpg" alt="haha" title="hehe" />', $sanitizer->GetXHTML(), 'Content within contentless tags should be dismissed' );
            $sanitizer->SetSource( '<img src="http://www.google.com/someimage.jpg" alt="haha" title="hehe">bw<span>aha</span>hah</img>' );
            $this->AssertEquals( '<img src="http://www.google.com/someimage.jpg" alt="haha" title="hehe" />', $sanitizer->GetXHTML(), 'Content and other tags within contentless tags should be dismissed' );
            $sanitizer->SetSource( '<img src="http://www.google.com/someimage.jpg" alt="haha" title="hehe">' );
            $this->AssertEquals( '<img src="http://www.google.com/someimage.jpg" alt="haha" title="hehe" />', $sanitizer->GetXHTML(), 'Contentless tags should be auto-closed shortly' );
        }
        public function TestLists() {
            $sanitizer = New XHTMLSanitizer();
            $ul = New XHTMLSaneTag( 'ul' );
            $ol = New XHTMLSaneTag( 'ol' );
            $li = New XHTMLSaneTag( 'li' );
            $sanitizer->AllowTag( $ul );
            $sanitizer->AllowTag( $ol );
            $sanitizer->AllowTag( $li );
            $sanitizer->SetSource( 'a-ha!<ul></ul>' );
            $this->AssertEquals( 'a-ha!<ul></ul>', $sanitizer->GetXHTML(), 'Empty <ul></ul> should be allowed' );
            $sanitizer->SetSource( 'a-ha!<ol></ol>' );
            $this->AssertEquals( 'a-ha!<ol></ol>', $sanitizer->GetXHTML(), 'Empty <ol></ol> should be allowed' );
            $sanitizer->SetSource( 'a-ha!<ul />' );
            $this->AssertEquals( 'a-ha!&lt;ul /&gt;', $sanitizer->GetXHTML(), '<ul> cannot be short-closed' );
            $sanitizer->SetSource( 'a-ha!<ol />' );
            $this->AssertEquals( 'a-ha!&lt;ol /&gt;', $sanitizer->GetXHTML(), '<ol> cannot be short-closed' );
            $sanitizer->SetSource( 'a-ha!<ul>I want to take you home</ul>' );
            $this->AssertEquals( 'a-ha!<ul><li>I want to take you home</li></ul>', $sanitizer->GetXHTML(), 'Text directly wrapped within <ul></ul> must be included in <li></li>' );
            $sanitizer->SetSource( '<ul>You\'re taking me over<li>over and over</li>I\'m falling over<li>one more time</li>happy end</ul>' );
            $this->AssertEquals( '<ul><li>You\'re taking me over</li><li>over and over</li><li>I\'m falling over</li><li>one more time</li><li>happy end</li></ul>', $sanitizer->GetXHTML(), 'Text directly wrapped within <ul></ul> must be included in separate <li></li>' );
            $sanitizer->SetSource( '<ul><ul>boo</ul></ul>' ); // --> <ul>boo</ul> --> <ul><li>boo</li></ul>
            $this->AssertEquals( '<ul><li>boo</li></ul>', $sanitizer->GetXHTML(), '<ul></ul> cannot contain subsequent <ul></ul>' );
            $sanitizer->SetSource( '<ul><ol><li>!</li></ol></ul>' );
            $this->AssertEquals( '<ul><li>!</li></ul>', $sanitizer->GetXHTML(), '<ul></ul> cannot contain subsequent <ol></ol> or <li></li>' );
            $sanitizer->SetSource( '<ul>            <li>The queerest of the queer</li>          </ul>' );
            $this->AssertEquals( '<ul><li>The queerest of the queer</li></ul>', $sanitizer->GetXHTML(), 'Whitespace directly within <ul></ul> should be eliminated' );
            $sanitizer->SetSource( '<uL><LI></lI></UL></UL><ULL><OL><LI>ha!</li><LI ha>mama</li></oL></ull>' );
            $this->AssertEquals( '<ul><li></li></ul>&lt;UL&gt;<ol><li>ha!</li><li>mama</li></ol>', $sanitizer->GetXHTML(), 'Insane lists should be sanitized' );
        }
        public function TestTables() {
            $sanitizer = New XHTMLSanitizer();
            $table = New XHTMLSaneTag( 'table' );
            $tbody = New XHTMLSaneTag( 'tbody' );
            $th = New XHTMLSaneTag( 'th' );
            $tr = New XHTMLSaneTag( 'tr' );
            $td = New XHTMLSaneTag( 'td' );
            $sanitizer->SetSource( '<table></table>' );
            $this->AssertEquals( '<table></table>', $sanitizer->GetXHTML(), 'Empty tables should be allowed' );
            $sanitizer->SetSource( '<table><tr><td></td></tr></table>' );
            $this->AssertEquals( '<table><tr><td></td></tr></table>', $sanitizer->GetXHTML(), 'Tables with one empty cell should be allowed' );
            $sanitizer->SetSource( '<table><tr><td>I</td><td>bet</td><td>you</td><td>\'d</td><td>die</td><td>to</td></tr><tr><td>garbage</td><td>I think</td><td>I\'m</td><td>paranoid</td></tr></table>' );
            $this->AssertEquals( '<table><tr><td>I</td><td>bet</td><td>you</td><td>\'d</td><td>die</td><td>to</td></tr><tr><td>garbage</td><td>I think</td><td>I\'m</td><td>paranoid</td></tr></table>', $sanitizer->GetXHTML(), 'Simple tables should be preserved' );
            $sanitizer->SetSource( '<table><tr>ha!</tr></table>' );
            $this->AssertEquals( '<table><tr><td>ha!</td></tr></table>', $sanitizer->GetXHTML(), 'Content directly wrapped within <tr> should be <td>\'d' );
            $sanitizer->SetSource( '<table><tr>ha!<td>need</td>me</tr></table>' );
            $this->AssertEquals( '<table><tr><td>ha!</td><td>need</td><td>me</td></tr></table>', $sanitizer->GetXHTML(), 'Content directly wrapped within <tr> should be <td>\'d even when other <td>s are present' );
            $sanitizer->SetSource( '<table>sup!</table>' ); // <table>sup!</table> --> <table><tr>sup!</tr></table> --> <table><tr><td>sup!</td></tr></table>
            $this->AssertEquals( '<table><tr><td>sup!</td></tr></table>', $sanitizer->GetXHTML(), 'Content directly wrapped within <table> must be converted to cell content' );
            $sanitizer->SetSource( '<table>har har <tr>ha!<td>need</td>me</tr> bwahhhah</table>' );
            $this->AssertEquals( '<table><tr><td>har har</tr><tr><td>ha!<td>need</td><td>me</td></tr><tr><td>bwahhhah</td></tr></table>', $sanitizer->GetXHTML(), 'Mysterious tables must be sanitized' );
            $sanitizer->SetSource( '<table><TBODY><tr><td>vampires</td><td>will</td><td>never</td><td>hurt</td></tr><tr><td>you</td></tr></TBODY></table>' );
            $this->AssertEquals( '<table><tr><td>vampires</td><td>will</td><td>never</td><td>hurt</td></tr><tr><td>you</td></tr></table>', $sanitizer->GetXHTML(), '<tbody> existence should be observed, but it should be removed' );
        }
        // real world examples
        public function TestRealWorld1() {
            $sanitizer = New XHTMLSanitizer();
            $div = New XHTMLSaneTag( 'div' );
            $ul = New XHTMLSaneTag( 'ul' );
            $li = New XHTMLSaneTag( 'li' );
            $a = New XHTMLSaneTag( 'a' );
            $strong = New XHTMLSaneTag( 'strong' );
            $img = New XHTMLSaneTag( 'img' );
            // notice that we aren't allowing $li->AllowAttribute( 'id' );
            $a->AllowAttribute( New XHTMLSaneAttribute( 'href' ) );
            // notice that we aren't allowing $a->AllowAttribute( 'target' );
            $img->AllowAttribute( New XHTMLSaneAttribute( 'class' ) );
            $img->AllowAttribute( New XHTMLSaneAttribute( 'src' ) );
            $img->AllowAttribute( New XHTMLSaneAttribute( 'width' ) );
            $img->AllowAttribute( New XHTMLSaneAttribute( 'height' ) );
            $img->AllowAttribute( New XHTMLSaneAttribute( 'alt' ) );
            $img->AllowAttribute( New XHTMLSaneAttribute( 'title' ) );
            $sanitizer->AllowTag( $div );
            $sanitizer->AllowTag( $ul );
            $sanitizer->AllowTag( $li );
            $sanitizer->AllowTag( $a );
            $sanitizer->AllowTag( $strong );
            $sanitizer->AllowTag( $img );
            $sanitizer->SetSource( // from phpMyAdmin
                  '<div><ul>'
                . '<li id="li_mysql_status"><a href="./server_status.php?token=7aa66b87fb60c5e787e97aff4d193f1e">Show MySQL runtime information</a>'
                . '</li><li id="li_mysql_variables"><a href="./server_variables.php?token=7aa66b87fb60c5e787e97aff4d193f1e">Show MySQL system variables</a>'
                . '<a href="http://dev.mysql.com/doc/refman/5.0/en/show-variables.html" target="mysql_doc"><img class="icon" src="./themes/original/img/b_help.png" width="11" height="11" alt="Documentation" title="Documentation" /></a></li><li id="li_mysql_processes"><a href="./server_processlist.php?token=7aa66b87fb60c5e787e97aff4d193f1e">Processes</a>'
                . '<a href="http://dev.mysql.com/doc/refman/5.0/en/show-processlist.html" target="mysql_doc"><img class="icon" src="./themes/original/img/b_help.png" width="11" height="11" alt="Documentation" title="Documentation" /></a></li><li id="li_mysql_collations"><a href="./server_collations.php?token=7aa66b87fb60c5e787e97aff4d193f1e">Character Sets and Collations</a>'
                . '</li><li id="li_mysql_engines"><a href="./server_engines.php?token=7aa66b87fb60c5e787e97aff4d193f1e">Storage Engines</a>'
                . '</li><li id="li_flush_privileges"><a href="./server_privileges.php?flush_privileges=1&amp;token=7aa66b87fb60c5e787e97aff4d193f1e">Reload privileges</a>'
                . '<a href="http://dev.mysql.com/doc/refman/5.0/en/flush.html" target="mysql_doc"><img class="icon" src="./themes/original/img/b_help.png" width="11" height="11" alt="Documentation" title="Documentation" /></a></li><li id="li_mysql_privilegs"><a href="./server_privileges.php?token=7aa66b87fb60c5e787e97aff4d193f1e">Privileges</a>'
                . '</li><li id="li_mysql_databases"><a href="./server_databases.php?token=7aa66b87fb60c5e787e97aff4d193f1e">Databases</a>'
                . '</li><li id="li_export"><a href="./server_export.php?token=7aa66b87fb60c5e787e97aff4d193f1e">Export</a>'
                . '</li><li id="li_import"><a href="./server_import.php?token=7aa66b87fb60c5e787e97aff4d193f1e">Import</a>'
                . '</li><li id="li_log_out"><a href="./index.php?token=7aa66b87fb60c5e787e97aff4d193f1e&amp;old_usr=root" target="_parent"><strong>Log out</strong> </a>'
                . '</li></ul></div>' 
            );
            $this->AssertEquals(
                  '<div><ul>'
                . '<li><a href="./server_status.php?token=7aa66b87fb60c5e787e97aff4d193f1e">Show MySQL runtime information</a>'
                . '</li><li><a href="./server_variables.php?token=7aa66b87fb60c5e787e97aff4d193f1e">Show MySQL system variables</a>'
                . '<a href="http://dev.mysql.com/doc/refman/5.0/en/show-variables.html"><img class="icon" src="./themes/original/img/b_help.png" width="11" height="11" alt="Documentation" title="Documentation" /></a></li><li><a href="./server_processlist.php?token=7aa66b87fb60c5e787e97aff4d193f1e">Processes</a>'
                . '<a href="http://dev.mysql.com/doc/refman/5.0/en/show-processlist.html"><img class="icon" src="./themes/original/img/b_help.png" width="11" height="11" alt="Documentation" title="Documentation" /></a></li><li><a href="./server_collations.php?token=7aa66b87fb60c5e787e97aff4d193f1e">Character Sets and Collations</a>'
                . '</li><li><a href="./server_engines.php?token=7aa66b87fb60c5e787e97aff4d193f1e">Storage Engines</a>'
                . '</li><li><a href="./server_privileges.php?flush_privileges=1&amp;token=7aa66b87fb60c5e787e97aff4d193f1e">Reload privileges</a>'
                . '<a href="http://dev.mysql.com/doc/refman/5.0/en/flush.html"><img class="icon" src="./themes/original/img/b_help.png" width="11" height="11" alt="Documentation" title="Documentation" /></a></li><li><a href="./server_privileges.php?token=7aa66b87fb60c5e787e97aff4d193f1e">Privileges</a>'
                . '</li><li><a href="./server_databases.php?token=7aa66b87fb60c5e787e97aff4d193f1e">Databases</a>'
                . '</li><li><a href="./server_export.php?token=7aa66b87fb60c5e787e97aff4d193f1e">Export</a>'
                . '</li><li><a href="./server_import.php?token=7aa66b87fb60c5e787e97aff4d193f1e">Import</a>'
                . '</li><li><a href="./index.php?token=7aa66b87fb60c5e787e97aff4d193f1e&amp;old_usr=root"><strong>Log out</strong> </a>'
                . '</li></ul></div>', $sanitizer->GetXHTML(), 'Real world example 1 failed'
            );
        }
        public function TestRealWorld2() {
            $sanitizer = New XHTMLSanitizer();
            $br = New XHTMLSaneTag( 'br' );
            $div = New XHTMLSaneTag( 'div' );
            $class = New XHTMLSaneAttribute( 'class' );
            $div->AllowAttribute( $class );
            $table = New XHTMLSaneTag( 'table' );
            $table->AllowAttribute( $class );
            $td = New XHTMLSaneTag( 'td' );
            $td->AllowAttribute( $class );
            $tr = New XHTMLSaneTag( 'tr' );
            $a = New XHTMLSaneTag( 'a' );
            $a->AllowAttributes( New XHTMLSaneAttribute( 'href' ) );
            
            $sanitizer->SetSource( // from water 3.0 fatal error
                  'Connection to MySQL failed:<br />Access denied for user \'(username)\'@\'localhost\' (using password: YES)'
                . '<br /><div id="bc_die_watertrace"><div class="watertrace"><table class="callstack"><tr><td class="title">revision'
                . '</td><td class="title">function</td><td class="title">source</td><td class="title">line</td></tr><tr><td class="revision">'
                . '</td><td class="function"><a href="http://www.php.net/include">include</a>( D:\htdocs\__dionyziz.com\header.php )</td>'
                . '<td class="file">D:\htdocs\__dionyziz.com\index.php</td><td class="line">6</td></tr><tr><td class="revision"></td>'
                . '<td class="function"><a href="http://www.php.net/include">include</a>( D:\htdocs\__dionyziz.com\lib\db.php )</td>'
                . '<td class="file">D:\htdocs\__dionyziz.com\header.php</td><td class="line">12</td></tr><tr><td class="revision">'
                . '</td><td class="function">Database-&gt;Authenticate( "(username)" , "(password)" )</td><td class="file">'
                . 'D:\htdocs\__dionyziz.com\lib\db.php</td><td class="line">59</td></tr><tr><td class="revision"></td>'
                . '<td class="function">w_die( "Connection to MySQL failed:<br..." )</td><td class="file">'
                . 'D:\htdocs\__dionyziz.com\lib\db.php</td><td class="line">25</td></tr><tr><td class="revision"></td>'
                . '<td class="function">Water-&gt;ThrowException( "Connection to MySQL failed:<br..." )</td><td class="file">'
                . 'D:\htdocs\__dionyziz.com\lib\water.php</td><td class="line">28</td></tr><tr><td class="revision"></td>'
                . '<td class="function">Water-&gt;HandleException( [object] )</td><td class="file">'
                . 'D:\htdocs\__dionyziz.com\lib\water.php</td><td class="line">37</td></tr><tr><td class="revision"></td>'
                . '<td class="function">Water-&gt;FatalError( "Connection to MySQL failed:<br..." )</td><td class="file">'
                . 'D:\htdocs\__dionyziz.com\lib\water.php</td><td class="line">65</td></tr><tr><td class="revision"></td>'
                . '<td class="function">Water-&gt;Trace()</td><td class="file">D:\htdocs\__dionyziz.com\lib\water.php</td>'
                . '<td class="line">100</td></tr><tr><td class="revision"></td><td class="function">Water-&gt;callstack_dump_lastword()</td>'
                . '<td class="file">D:\htdocs\__dionyziz.com\lib\water.php</td><td class="line">117</td></tr><tr><td class="revision">'
                . '</td><td class="function">Water-&gt;callstack_lastword()</td><td class="file">D:\htdocs\__dionyziz.com\lib\water.php'
                . '</td><td class="line">293</td></tr></table></div></div></div>'
            );
            $this->AssertEquals( 
                  'Connection to MySQL failed:<br />Access denied for user \'(username)\'@\'localhost\' (using password: YES)'
                . '<br /><div><div class="watertrace"><table class="callstack"><tr><td class="title">revision'
                . '</td><td class="title">function</td><td class="title">source</td><td class="title">line</td></tr><tr><td class="revision">'
                . '</td><td class="function"><a href="http://www.php.net/include">include</a>( D:\htdocs\__dionyziz.com\header.php )</td>'
                . '<td class="file">D:\htdocs\__dionyziz.com\index.php</td><td class="line">6</td></tr><tr><td class="revision"></td>'
                . '<td class="function"><a href="http://www.php.net/include">include</a>( D:\htdocs\__dionyziz.com\lib\db.php )</td>'
                . '<td class="file">D:\htdocs\__dionyziz.com\header.php</td><td class="line">12</td></tr><tr><td class="revision">'
                . '</td><td class="function">Database-&gt;Authenticate( "(username)" , "(password)" )</td><td class="file">'
                . 'D:\htdocs\__dionyziz.com\lib\db.php</td><td class="line">59</td></tr><tr><td class="revision"></td>'
                . '<td class="function">w_die( "Connection to MySQL failed:&lt;br..." )</td><td class="file">'
                . 'D:\htdocs\__dionyziz.com\lib\db.php</td><td class="line">25</td></tr><tr><td class="revision"></td>'
                . '<td class="function">Water-&gt;ThrowException( "Connection to MySQL failed:&lt;br..." )</td><td class="file">'
                . 'D:\htdocs\__dionyziz.com\lib\water.php</td><td class="line">28</td></tr><tr><td class="revision"></td>'
                . '<td class="function">Water-&gt;HandleException( [object] )</td><td class="file">'
                . 'D:\htdocs\__dionyziz.com\lib\water.php</td><td class="line">37</td></tr><tr><td class="revision"></td>'
                . '<td class="function">Water-&gt;FatalError( "Connection to MySQL failed:<br..." )</td><td class="file">'
                . 'D:\htdocs\__dionyziz.com\lib\water.php</td><td class="line">65</td></tr><tr><td class="revision"></td>'
                . '<td class="function">Water-&gt;Trace()</td><td class="file">D:\htdocs\__dionyziz.com\lib\water.php</td>'
                . '<td class="line">100</td></tr><tr><td class="revision"></td><td class="function">Water-&gt;callstack_dump_lastword()</td>'
                . '<td class="file">D:\htdocs\__dionyziz.com\lib\water.php</td><td class="line">117</td></tr><tr><td class="revision">'
                . '</td><td class="function">Water-&gt;callstack_lastword()</td><td class="file">D:\htdocs\__dionyziz.com\lib\water.php'
                . '</td><td class="line">293</td></tr></table></div></div>&lt;/div&gt;',
                $sanitizer->GetXHTML(), 'Real world example 2 failed'
            );
        }
        public function TestRealWorld3() {
            $sanitizer = New XHTMLSanitizer();
            $table = New XHTMLSaneTag( 'table' );
            $table->AllowAttribute( New XHTMLSaneAttribute( 'class' ) );
            $tr = New XHTMLSaneTag( 'tr' );
            $td = New XHTMLSaneTag( 'td' );
            $img = New XHTMLSaneTag( 'img' );
            $img->AllowAttribute( New XHTMLSaneAttribute( 'src' ) );
            $a = New XHTMLSaneTag( 'a' );
            $a->AllowAttribute( New XHTMLSaneAttribute( 'href' ) );
            $a->AllowAttribute( New XHTMLSaneAttribute( 'class' ) );
            $div = New XHTMLSaneTag( 'div' );
            $div->AllowAttribute( New XHTMLSaneAttribute( 'class' ) );
            $b = New XHTMLSaneTag( 'b' );
            $br = New XHTMLSaneTag( 'br' );
            $small = New XHTMLSaneTag( 'small' );
            $h2 = New XHTMLSaneTag( 'h2' );
            $sanitizer->SetSource( // from Orange Juice 3.2
                  '<table class="banner" style="width:100%;" cellpadding="0" cellspacing="0">'
                . '<tr>'
                . '<td><img src="music.jpg" /></td>'
                . '<td class="ver">'
                . '<a href="index.php" class="vll">Home</a>'
                . '<a href="top.php" class="vl">Favorites</a>'
                . '<a href="played.php" class="vl">History</a>'
                . '<a href="equalizer.php" class="vl">Volume</a>'
                . '<a href="about.php" class="vl">About</a> '
                . '</td>'
                . '</tr>'
                . '</table>'
                . '<div class="maincontent"><h2><img src="images/big_orange.png" /> Orange Juice</h2>'
                . '<table style="border: 1px solid gray;background-color:#f0f5ff">'
                . '<tr><td>'
                . '<table><tr><td><img src="images/current.png"></td><td>'
                . 'Now playing: <a href="song.php?id=1020"><b>Cross me off your list</b>'
                . '</a> by <a href="artist.php?a=Hawthorne Heights"><b>Hawthorne Heights</b></a>,'
                . '<small><a href="song.php?id=1020#lyrics">Lyrics</a></small></td>'
                . '</tr></table></td></tr><tr><td><table><tr><td><img src="images/pause.png" />'
                . '</td><td><b><a href="pause.php">Pause</a></b></td><td /><td>'
                . '<img src="images/replay.png" /></td><td><b><a href="play.php?id=1020">Replay</a>'
                . '</td><td /><td><img src="images/next.png" /></td><td><b><a href="next.php">'
                . 'Next</a><b/></td></tr></table>'
                . '</td></tr></table><br /><table><tr><td><img src="images/lock.png" />'
                . '</td><td><b>Locked to <a href="artist.php?a=Hawthorne Heights">Hawthorne Heights'
                . '</a></b></td><td><img src="images/dounlock.png" /></td><td><a href="lock.php">'
                . 'Unlock</a></td></tr></table><br />Songs in library: <b>1093</b>, <small>'
                . '181 of which contain lyrics.</small><br /><br /><table><tr><td>'
                . '<img src="images/fav.png"></td><td><a href="top.php">Favorite Songs</a>'
                . '</td></tr></table>'
                . '<table><tr><td><img src="images/history.png"></td><td><a href="played.php">'
                . 'History</a></td></tr></table><table><tr><td><img src="images/uptime.png" />'
                . '</td><td>Uptime: <b>151 days 20 hours 5 minutes and 57 seconds</b></td></tr>'
                . '</table><br />'
                . '<table><tr><td><img src="images/update.png" /></td><td><a href="clean_up.php">'
                . 'Update Library</a></td></tr></table>'
                . '<table><tr><td><img src="images/root.png" /></td><td>Music Root: </td><td><b>'
                . 'D:\music</b></td></tr></table><br /></div>'
            );
            $this->AssertEquals(
                  '<table class="banner">'
                . '<tr>'
                . '<td><img src="music.jpg" alt="" /></td>'
                . '<td class="ver">'
                . '<a href="index.php" class="vll">Home</a>'
                . '<a href="top.php" class="vl">Favorites</a>'
                . '<a href="played.php" class="vl">History</a>'
                . '<a href="equalizer.php" class="vl">Volume</a>'
                . '<a href="about.php" class="vl">About</a> '
                . '</td>'
                . '</tr>'
                . '</table>'
                . '<div class="maincontent"><h2><img src="images/big_orange.png" alt="" /> Orange Juice</h2>'
                . '<table>'
                . '<tr><td>'
                . '<table><tr><td><img src="images/current.png" alt="" /></td><td>'
                . 'Now playing: <a href="song.php?id=1020"><b>Cross me off your list</b>'
                . '</a> by <a href="artist.php?a=Hawthorne Heights"><b>Hawthorne Heights</b></a>,'
                . '<small><a href="song.php?id=1020#lyrics">Lyrics</a></small></td>'
                . '</tr></table></td></tr><tr><td><table><tr><td><img src="images/pause.png" alt="" />'
                . '</td><td><b><a href="pause.php">Pause</a></b></td><td /><td>'
                . '<img src="images/replay.png" /></td><td><b><a href="play.php?id=1020">Replay</a>'
                . '</td><td /><td><img src="images/next.png" alt="" /></td><td><b><a href="next.php">'
                . 'Next</a><b/></td></tr></table>'
                . '</td></tr></table><br /><table><tr><td><img src="images/lock.png" alt="" />'
                . '</td><td><b>Locked to <a href="artist.php?a=Hawthorne Heights">Hawthorne Heights'
                . '</a></b></td><td><img src="images/dounlock.png" alt="" /></td><td><a href="lock.php">'
                . 'Unlock</a></td></tr></table><br />Songs in library: <b>1093</b>, <small>'
                . '181 of which contain lyrics.</small><br /><br /><table><tr><td>'
                . '<img src="images/fav.png" alt="" /></td><td><a href="top.php">Favorite Songs</a>'
                . '</td></tr></table>'
                . '<table><tr><td><img src="images/history.png" alt="" /></td><td><a href="played.php">'
                . 'History</a></td></tr></table><table><tr><td><img src="images/uptime.png" />'
                . '</td><td>Uptime: <b>151 days 20 hours 5 minutes and 57 seconds</b></td></tr>'
                . '</table><br />'
                . '<table><tr><td><img src="images/update.png" alt="" /></td><td><a href="clean_up.php">'
                . 'Update Library</a></td></tr></table>'
                . '<table><tr><td><img src="images/root.png" alt="" /></td><td>Music Root: </td><td><b>'
                . 'D:\music</b></td></tr></table><br /></div>',
                $sanitizer->GetXHTML(), 'Real world example 3 failed'
            );
        }
    }
    
    return new TestXHTMLSanitizer();
?>
