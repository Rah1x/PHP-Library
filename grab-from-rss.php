<?php
## function grab_rss(Dynamic parameters)
## grab_links(html/xml, is xml, to find, rename found elements for returning, parent element);
function grab_rss()
{
    $collection = array();
    $arg_list = func_get_args();

    $html_contents = $arg_list[0];
    $to_find = explode('|', $arg_list[1]);
    $save_as = explode('|', $arg_list[2]);
    $tags = explode('>', $arg_list[3]);

    $dom = new DOMDocument();
    $dom->loadXML($html_contents);


    $tgs = $dom->getElementsByTagName($tags[0]);
    for($i=0; $i<$tgs->length; $i++)
    {
        $inner = $tgs->item($i);

        $ix = 0;
        foreach($to_find as $fi)
        {
            $node = $inner->getElementsByTagName($fi);
            $node_data = $node->item(0)->nodeValue;

            ## Format String
            $node_data = htmlentities($node_data, ENT_QUOTES, 'UTF-8');
            $node_data = format_str3($node_data);
            $collection[$i][$save_as[$ix]] = $node_data;
            $ix++;
        }
    }
    //var_dump($collection); die();

    return $collection;

}//end func.........
?>