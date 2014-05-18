<?php

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2007, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

// Help file in German
?>

<h3><strong><img src="img/top_help.gif" width="41" height="47" align="left" /></strong><br />
Help for the module &quot;Members&quot;</h3>
<p>What is &quot;Members&quot;<br />
  Help for users<br />
  Help for admins<br />
  Tips/Tricks<br />
Known problems</p>
<h2>What is &quot;Members&quot;</h2>
<p>&quot;Members&quot; is a very flexible module for managing and sorting persons,
  things, items. Members can be used for rankings, part lists, portfolios, menu
  cards (for restaurants), playlists, lists of participants and many more. You
  can also use
  members for special kinds of menus, for example with pictures or gallery-overviews.<br />
  A member can have aliases (proxies) and thereby
  be in more groups - also on different pages.<br />
  &quot;Members&quot; can be configured in
  many different ways. An administrator can set a lot of own adjustments, so
  the help for users is sometimes abstract.</p>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td width="14%">Placeholder</td>
    <td width="86%">Example Output/Notes</td>
  </tr>
  <tr>
    <td>Header/Footer</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>[WB_URL]<br>
[PAGE_TITLE]<br>
[MENU_TITLE]<br>
[PAGES_DIRECTORY]<br>
[MEDIA_DIRECTORY]<br>
[LANGUAGE]</td>
    <td><p>Like the WB-Constants.</p>
    <p>Note: These placeholders will also be replaced when caching is active.</p></td>
  </tr>
  <tr>
    <td>[LIST_GROUP_LINKS]<br>
[LIST_GROUP_LINKS_FULL]</td>
    <td>Header only: Provides a listet menue of groups and items</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>[REVERSESORTING]</td>
    <td>eg: Sort by : &lt;a href=&quot;?sort=1&quot;&gt;Position&lt;/a&gt; | &lt;a
      href=&quot;?sort=2&quot;&gt;Name&lt;/a&gt; | &lt;a href=&quot;?sort=3&quot;&gt;Sorter&lt;/a&gt; | &lt;a
      href=&quot;?sort=4&quot;&gt;Score&lt;/a&gt; | &lt;a class=&quot;members-showsortlink[REVERSESORTING]&quot; href=&quot;?sort=[REVERSESORTING]&quot;&gt;reverse&lt;/a&gt;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>[GROUP_ID]</td>
    <td>2 (Number of the group, unchangeable)</td>
  </tr>
  <tr>
    <td>[GROUPNAME]</td>
    <td>The name of the group</td>
  </tr>
  <tr>
    <td>[GROUPDESC]</td>
    <td>The description of the group</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>[MEMBER_ID]</td>
    <td>23 (Number of the member/alias, unchangeable)</td>
  </tr>
  <tr>
    <td>[PICNAME]</td>
    <td>&quot;filename.jpg&quot; The name of the picture as is.</td>
  </tr>
  <tr>
    <td>[PICTURE]<br />
&nbsp;</td>
    <td>"http://.... media/members/thing.jpg" OR "http://...modules/members/img/nopic.jpg"<br />
    </td>
  </tr>
  <tr>
    <td>[NAME]</td>
    <td>Member name</td>
  </tr>
  <tr>
    <td>[SCORE]</td>
    <td>1056 (Number, changeable, used for sorting)</td>
  </tr>
  <tr>
    <td>[SHORT1]<br/>
      [SHORT2]</td>
    <td>Short text 1 / 2<br />
      Note: Backshlash \ makes &lt;br/&gt;</td>
  </tr>
  <tr>
    <td>[LONG1]<br/>
      [LONG2]</td>
    <td>Long text 1<br />
      Long text 2</td>
  </tr>
  <tr>
    <td>[LINK]<br />
&nbsp;</td>
    <td><p>External link oder email-address, see above</p>
    </td>
  </tr>
  <tr>
    <td>[MEMBERPAGE]<br />
&nbsp;</td>
    <td>22 ($page_id) -&gt; http://www.mydomain.com/thispage.php (Link to internal
      page, does not break when you move the page)<br />
      Note: [MEMBERPAGE] does NOT contain a link-tag &lt;a href....&gt; see {MEMBERPAGE}</td>
  </tr>
  <tr>
    <td>[SORTT]</td>
    <td>T180 (a short string, used for sorting)</td>
  </tr>
  <tr>
    <td>[MROW]</td>
    <td>1 or 0 alternating</td>
  </tr>
  <tr>
    <td>[ROWCOUNT]</td>
    <td>1 ( counter, number per group)</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><br />
      Those placeholders in {} have only output complete with &lt;div...&gt; &lt;/div&gt;,
      when the field has content. <br />
      To prevent from empty boxes &lt;div..&gt;&lt;/div&gt; which could cause
      troubles.</td>
  </tr>
  <tr>
    <td>{GROUPNAME}<br />
      {GROUPDESC}</td>
    <td>&lt;div class=&quot;mgroup-name&quot;&gt;Groupname&lt;/div&gt;<br />
&lt;div class=&quot;mgroup-desc&quot;&gt;Group description&lt;/div&gt; </td>
  </tr>
  <tr>
    <td>{SCORE}<br/>
      {SHORT1}<br />
      {SHORT2} <br/>
      {LONG1} <br/>
      {LONG2}<br/>
      {LINK}<br/>
      {MEMBERPAGE}<br/>
      {SORTT}</td>
    <td>&lt;div class=&quot;member-score&quot;&gt;1056&lt;/div&gt;<br />
  &lt; 
      div class=&quot;member-short1&quot;&gt;Short text 1&lt;/div&gt;<br />
&lt;div class=&quot;member-short2&quot;&gt;Short text 2&lt;/div&gt; <br />
&lt;div class=&quot;member-long1&quot;&gt;Long text 1&lt;/div&gt;<br />
&lt;div class=&quot;member-long2&quot;&gt;Long text 2&lt;/div&gt;<br />
&lt;div class=&quot;member-link&quot;&gt;see [LINK] &lt;/div&gt; <br />
&lt;div class=&quot;member-page&quot;&gt;&lt;a href=&quot;see [MEMBERPAGE]&quot;&gt; Title
of the page&lt;/a&gt;&lt;/div&gt; <br />
&lt;div class=&quot;member-sortt&quot;&gt;T180&lt;/div&gt;</td>
  </tr>
</table>
<p>&nbsp;</p>
<h2><br />
Help for users</h2>
<table width="100%" border="0" cellspacing="0" cellpadding="6">
  <tr valign="top">
    <td width="90"><div align="center">
      <p><strong><img src="img/top_new.gif" width="80" height="47" /><img src="img/modg.gif" width="22" height="22" /></strong></p>
    </div></td>
    <td><strong>Add Group</strong><br />
      <strong>Modify Group</strong><br />
      Name and description
    of the group, appears (usually) above the list.</td>
  </tr>
  <tr valign="top">
    <td width="90"><div align="center"><strong><img src="img/top_options.gif" width="41" height="47" /></strong></div></td>
    <td><p><strong>Options</strong><br />
        <strong>Note:</strong> When you move a member or its alias from one section/page
        to another, there might be troubles or strange appearance if the sections/pages
        have different settings. You should use the same settings for those sections/pages
        then. Particularly you should have the same directory for all pictures.</p>
    </td>
  </tr>
  <tr valign="top">
    <td><img src="img/top_sort2.gif" width="41" height="47"><img src="img/top_sort1.gif" width="41" height="47"></td>
    <td><strong>Sort</strong> (Drag&amp;Drop / Normal)<br>
Only aktive if sorting is set to &quot;by position, ASC&quot;</td>
  </tr>
  <tr valign="top">
    <td width="90"><div align="center"><strong><img src="img/top_ghosts.gif" width="41" height="47" /></strong></div></td>
    <td><p><strong>Manage ghosts</strong><br />
        If a member is deleted, it stays as a &quot;ghost&quot;. A ghost does
        not belong to a group, and in this area you can move it to another/new
        group
            or finally delete it.<br />
            Keep on your mind: If you delete a ghost which has aliases (= has
            a number in brackets near the name) the aliases will also be deleted
            - on
            any
            page, everywhere!</p>
    </td>
  </tr>
  <tr valign="top">
    <td width="90"><p align="center"><strong><img src="img/addgm.gif" width="22" height="22" /></strong></p>
    <p align="center"><strong><img src="img/modm.gif" width="16" height="16" /></strong></p></td>
    <td><p><strong>Add Member<br />
       </strong><strong>Modify </strong><strong>Member</strong><br />
       In the left segment you can add/move a member to different groups. The
       options-entries have different colors, deppending on: same section, different
       section
       but the same page, different page. There are also some fields used for
       sorting and an options menu with pictures in the presetted directory media/members/</p>
      <p>In the right segment there are some input fields, the field-names can
        be changed in the options, also if there is html allowed or not.<br />
        Tip: In the textares line breaks persist, in the short imput fields a
        backslash \ creates a line break. (&lt;br/&gt;)</p>
      <p>&nbsp;</p>
    </td>
  </tr>
  <tr valign="top">
    <td width="90"><div align="center"><img src="img/addalias.gif" width="16" height="16" /></div></td>
    <td><p><strong>Add alias</strong><br />
        An alias is a placeholder/proxy for a member. So a member can be in more
            than one group and can be sorted in different ways. An alias name
            is displayed in <em>oblique.</em></p>
      <p>You can also create
              an alias of  an alias, which is gonna be an alias of the original
        member.<br />
        If a member has aliases,
      there is a bracket with the number near the name.</p>
      <p>If you manage an alias,
          there are no fields but the field-content of the original member. You
          can change only the sorting/score fields. You
        cannot change the name or any other property of an alias.</p></td>
  </tr>
  <tr valign="top">
    <td width="90"></td>
    <td><p>&nbsp;</p>
    </td>
  </tr>
  <tr valign="top">
    <td width="90"></td>
    <td><p><strong>Active: yes/no</strong><br />
        A inactive member stays in his group, but is not displayed - that is
            the difference to a ghost: a ghost is in no group and always invisible
            on the page.</p>
      <p>Note: If a member is inactive, its alias is also inactive. If a member
        is a ghost, its alias may be visible.</p></td>
  </tr>
</table>
<h2> Help for admins:</h2>
<p>Besides the WB-typical settings there is a file <em>module-settings.php</em> in
  the directory
<em>modules/members/ </em>, where some additional settings and defaults can be
made. Check it out!</p>
<p><strong>Options [2]</strong></p>
<p>In the second section of the settings-page you can preset the names of the
  fields. If a field has no name, it will not be displayed in the backend and
  in the
  frontend,
  and
  it
  is also not editable on the 'add/modify member' page. Note: If a field is not
  displayed/editable, it looses its content, when the member is saved again.</p>
<p>There are  each 2 long and short fields for free usage. Additional there
  is a field &quot;link&quot; which can contain a link OR an email-address.After
  this there can be additional text, which is used as linktext:<br />
  Example:<font color="#000099"><br />
  media@beesign.com Chio Maisriml</font> will be <a href="mailto:media@beesign.com">Chio
  Maisriml</a> (optional masked with  Javascript)<br />
  <font color="#000099">http://www.beesign.com Chio&#8217;s page</font> will
be <a href="http://www.beesign.com" target="_blank">Chio&#8217;s page</a></p>
<p>  The optional field memberpage_id can contain a page_id
  of a internal page (Number!)</p>
<p><strong>Options [3]</strong><br />
  The 3. section contains the WB-typical settings for the output. There are
    a lot of possibilities.<br />
    <strong>Note</strong>: mostly there is [FIELD] and also {FIELD}
(mind the brackets!)<br />
The difference: [FIELD] is a placeholder for the content of the field only (as
usual in Website Baker)<br />
{FIELD}is a complete tag with &lt;div class=&quot;theclass&quot;&gt;Content&lt;/div&gt;, sometimes
&lt;a href={FIELD}&gt;some text&lt;a&gt;<br />
Use {FIELD}to prevent empty boxes or links when a field could have no content.</p>
<h2>Tips/Tricks</h2>
<p>  See german page: http://www.beesign.com/websitebaker/members/</p>
<h2>Problems/Troubleshooting:</h2>
<p>There are some &quot;logical&quot; problems and conflicts between different
  possibilities. For example if you move members from one page to another and
  there are different
  settings.</p>
<p><strong>Caching:</strong><br />
By default caching is off. If your page is finished you can activate it to make
the output faster. Caching works very simple: Every time there is a change in
the backend, the cache will be flushed. The first time one sees a page on the
frontend it will be renewed. So if you want to renew the cache, save something
in the backend (there is no need to change anything)<br />
</p>
<hr />
<p>Note: Field &quot;m_Link&quot;: This field can contain a email-address OR
  a link. If the first word starts  http:// 
  (=Link)
  OR contains
  a &quot;@&quot; and&quot;.&quot;  (email). Additional text is displayed as
  a link text.<br />
  Example: &quot;<em>gerti@maier.com Miss Gerti Meier</em>&quot; --&gt; &quot;&lt;a
  href=&quot;mailto:gerti@maier.com&gt;Miss
Gerti Meier&lt;/a&gt;.</p>
<p>&nbsp;</p>
<p><a href="http://websitebaker.at/wb/members.html" target="_blank">Additional
    information</a> (german)</p>
