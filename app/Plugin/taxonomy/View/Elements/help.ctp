<style>
    #vocabularyList ul {
        list-style-position: outside;
        list-style-type: square;
        margin-left:15px;
    }
</style>
<h3>About</h3>
<p>
    The Taxonomy module allows you to classify the content of your website.
    To classify content, you define <em>vocabularies</em> that contain related <em>terms</em>, and then assign the vocabularies to content types.
</p>

<h3>Uses</h3>
<dl>
    <dt>Creating vocabularies</dt>
    <dd>
        Users with sufficient <a href="<?php echo $this->Html->url('/admin/user/permissions/'); ?>">permissions</a> can create <em>vocabularies</em> and <em>terms</em> through the <a href="<?php echo $this->Html->url('/admin/taxonomy/vocabularies'); ?>">Taxonomy page</a>.
        The page listing the terms provides an interface for controlling the order of the terms and sub-terms within a vocabulary, in a hierarchical fashion.
        A <em>controlled vocabulary</em> classifying music by genre with terms and sub-terms could look as follows:
            <div id="vocabularyList">
                <ul>
                    <li><em>vocabulary</em>: Music</li>
                        <ul>
                            <li><em>term</em>: Jazz</li>
                            <ul>
                                <li><em>sub-term</em>: Swing</li>
                                <li><em>sub-term</em>: Fusion</li>
                            </ul>
                        </ul>

                        <ul>
                            <li><em>term</em>: Rock</li>
                            <ul>
                                <li><em>sub-term</em>: Country rock</li>
                                <li><em>sub-term</em>: Hard rock</li>
                            </ul>
                        </ul>
                </ul>
            </div>
    </dd>

    <dt>Assigning vocabularies to content types</dt>
    <dd>
        Before you can classify your content, you must assign vocabularies to your
        <a href="<?php echo $this->Html->url('/admin/node/types'); ?>">content type</a> on its <em>edit or create form</em> page (multiple vocabularies can be chosen).
        The same vocabulary can be added to multiple content types.
    </dd>

    <dt>Classifying content</dt>
    <dd>
        After the vocabulary is assigned to the content type, you can start classifying content.
        You can choose the desired terms for your content on its <em>edit</em> or <em>add content</em> page
        (whether one or multiple terms can be chosen from the vocabulary).
    </dd>

    <dt>Viewing listings and RSS feeds by term</dt>
    <dd>
        Each taxonomy term automatically provides a page listing content that has its classification,
        and a corresponding RSS feed. For example, if the taxonomy term <em>country rock</em>
        has the ID 123 (you can see this by looking at the URL when hovering on the linked term,
        which you can click to navigate to the listing page), then you will find this list at the path <em>taxonomy/term/123</em>.
        The RSS feed will use the path <em>taxonomy/term/123/feed</em>
        (the RSS icon for this term's listing will automatically display in your browser's address bar when viewing the listing page).
    </dd>
</dl>