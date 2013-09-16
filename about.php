<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="css/jquery.jOrgChart.css"/>
        <link rel="stylesheet" href="css/jquery.jOrgChart.customizable.css"/>
        <link rel="stylesheet" href="css/mycustom.css"/>
        <title>About</title>
    </head>

    <body>
        <?php require('include/navigation.html'); ?>

        <div>
            <img class="background" src="images/Facebook_in_the_dark_widewall_by_will_yen.jpg">
        </div>
        
        <div align="center" style="margin-top: 50px;">
            <!--About description box -->
            <div style="margin-left: 50px; text-align:left;"class="box">
                <h2>About</h2>
                <p>
                    Write stories with alternative endings or parallel universes.<br />
                    Collaborate with other users to create a story tree.<br />
                    Share your stories or make them private.<br />
                </p><br />

                <p>
                    The tree diagram displayed here is a Story Tree. 
                    A Story Tree can be represented as a book.
                    Each node (rectangular box) represents a chapter. 
                    There can only be one beginning chapter in a Story Tree.
                    In this example, there are six story lines.
                    <ol>
                        <li>Chapter 1 -> Chapter2a</li>
                        <li>Chapter 1 -> Chapter2b -> Chapter3a</li>
                        <li>Chapter 1 -> Chapter2b -> Chapter3b</li>
                        <li>Chapter 1 -> Chapter2c</li>
                        <li>Chapter 1 -> Chapter2d -> Chapter3c</li>
                        <li>Chapter 1 -> Chapter2d -> Chapter3d</li>
                    </ol>
                </p>
            </div>
        
            <!--Story Tree full output from javaScript jOrgChart plugin -->
            <div class="jOrgChart">
                <table cellpadding="0" cellspacing="0" border="0">
                    <tbody>
                        <tr class="node-cells">
                            <td class="node-cell" colspan="8"><div class="node" style="cursor: n-resize;"><a href="#" target="_blank">Chapter 1</a><br><font size="1px"><i>Author: Ray</i></font></div></td>
                        </tr>
                        <tr>
                            <td colspan="8"><div class="line down"></div></td>
                        </tr>
                        <tr>
                            <td class="line left">&nbsp;</td>
                            <td class="line right top">&nbsp;</td>
                            <td class="line left top">&nbsp;</td>
                            <td class="line right top">&nbsp;</td>
                            <td class="line left top">&nbsp;</td>
                            <td class="line right top">&nbsp;</td>
                            <td class="line left top">&nbsp;</td>
                            <td class="line right">&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="node-container" colspan="2">
                                <table cellpadding="0" cellspacing="0" border="0">
                                    <tbody>
                                        <tr class="node-cells">
                                            <td class="node-cell" colspan="2"><div class="node"><a href="#" target="_blank">Chapter 2a</a></div></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td class="node-container" colspan="2">
                                <table cellpadding="0" cellspacing="0" border="0">
                                    <tbody>
                                        <tr class="node-cells"> 
                                            <td class="node-cell" colspan="4"><div class="node" style="cursor: n-resize;"><a href="#" target="_blank">Chapter 2b</a></div></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"><div class="line down"></div></td>
                                        </tr>
                                        <tr>
                                            <td class="line left">&nbsp;</td>
                                            <td class="line right top">&nbsp;</td>
                                            <td class="line left top">&nbsp;</td>
                                            <td class="line right">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td class="node-container" colspan="2">
                                                <table cellpadding="0" cellspacing="0" border="0">
                                                    <tbody>
                                                        <tr class="node-cells">
                                                            <td class="node-cell" colspan="2"><div class="node"><a href="#" target="_blank">Chapter 3a</a></div></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td class="node-container" colspan="2">
                                                <table cellpadding="0" cellspacing="0" border="0">
                                                    <tbody>
                                                        <tr class="node-cells">
                                                            <td class="node-cell" colspan="2"><div class="node"><a href="#" target="_blank">Chapter 3b</a></div></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td class="node-container" colspan="2">
                                <table cellpadding="0" cellspacing="0" border="0">
                                    <tbody>
                                        <tr class="node-cells">
                                            <td class="node-cell" colspan="2"><div class="node"><a href="#" target="_blank">Chapter 2c</a><br><font size="1px"><i>Author: Anonymous</i></font></div></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td class="node-container" colspan="2">
                                <table cellpadding="0" cellspacing="0" border="0">
                                    <tbody>
                                        <tr class="node-cells">
                                            <td class="node-cell" colspan="4"><div class="node" style="cursor: n-resize;"><a href="#" target="_blank">Chapter 2d</a></div></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"><div class="line down"></div></td>
                                        </tr>
                                        <tr>
                                            <td class="line left">&nbsp;</td>
                                            <td class="line right top">&nbsp;</td>
                                            <td class="line left top">&nbsp;</td>
                                            <td class="line right">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td class="node-container" colspan="2">
                                                <table cellpadding="0" cellspacing="0" border="0">
                                                    <tbody>
                                                        <tr class="node-cells">
                                                            <td class="node-cell" colspan="2"><div class="node"><a href="#" target="_blank">Chapter 3c</a></div></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td class="node-container" colspan="2">
                                                <table cellpadding="0" cellspacing="0" border="0">
                                                    <tbody>
                                                        <tr class="node-cells">
                                                            <td class="node-cell" colspan="2"><div class="node"><a href="#" target="_blank">Chapter 3d</a></div></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>  
                    </tbody>
                </table>
            </div> <!--End Story Tree -->
        </div>
    </body>
</html>
