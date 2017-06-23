
window.projectVersion = '0.10.0';

(function(root) {

    var bhIndex = null;
    var rootPath = '';
    var treeHtml = '        <ul>                <li data-name="namespace:Stampie" class="opened">                    <div style="padding-left:0px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Stampie.html">Stampie</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="namespace:Stampie_Adapter" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Stampie/Adapter.html">Adapter</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Stampie_Adapter_AdapterInterface" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Stampie/Adapter/AdapterInterface.html">AdapterInterface</a>                    </div>                </li>                            <li data-name="class:Stampie_Adapter_Buzz" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Stampie/Adapter/Buzz.html">Buzz</a>                    </div>                </li>                            <li data-name="class:Stampie_Adapter_Guzzle" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Stampie/Adapter/Guzzle.html">Guzzle</a>                    </div>                </li>                            <li data-name="class:Stampie_Adapter_NoopAdapter" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Stampie/Adapter/NoopAdapter.html">NoopAdapter</a>                    </div>                </li>                            <li data-name="class:Stampie_Adapter_Response" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Stampie/Adapter/Response.html">Response</a>                    </div>                </li>                            <li data-name="class:Stampie_Adapter_ResponseInterface" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Stampie/Adapter/ResponseInterface.html">ResponseInterface</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:Stampie_Exception" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Stampie/Exception.html">Exception</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Stampie_Exception_ApiException" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Stampie/Exception/ApiException.html">ApiException</a>                    </div>                </li>                            <li data-name="class:Stampie_Exception_HttpException" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Stampie/Exception/HttpException.html">HttpException</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:Stampie_Mailer" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Stampie/Mailer.html">Mailer</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Stampie_Mailer_MailGun" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Stampie/Mailer/MailGun.html">MailGun</a>                    </div>                </li>                            <li data-name="class:Stampie_Mailer_Mandrill" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Stampie/Mailer/Mandrill.html">Mandrill</a>                    </div>                </li>                            <li data-name="class:Stampie_Mailer_Postmark" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Stampie/Mailer/Postmark.html">Postmark</a>                    </div>                </li>                            <li data-name="class:Stampie_Mailer_SendGrid" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Stampie/Mailer/SendGrid.html">SendGrid</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:Stampie_Message" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Stampie/Message.html">Message</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Stampie_Message_MetadataAwareInterface" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Stampie/Message/MetadataAwareInterface.html">MetadataAwareInterface</a>                    </div>                </li>                            <li data-name="class:Stampie_Message_TaggableInterface" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Stampie/Message/TaggableInterface.html">TaggableInterface</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="namespace:Stampie_Util" class="opened">                    <div style="padding-left:18px" class="hd">                        <span class="glyphicon glyphicon-play"></span><a href="Stampie/Util.html">Util</a>                    </div>                    <div class="bd">                                <ul>                <li data-name="class:Stampie_Util_IdentityUtils" >                    <div style="padding-left:44px" class="hd leaf">                        <a href="Stampie/Util/IdentityUtils.html">IdentityUtils</a>                    </div>                </li>                </ul></div>                </li>                            <li data-name="class:Stampie_Identity" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="Stampie/Identity.html">Identity</a>                    </div>                </li>                            <li data-name="class:Stampie_IdentityInterface" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="Stampie/IdentityInterface.html">IdentityInterface</a>                    </div>                </li>                            <li data-name="class:Stampie_Mailer" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="Stampie/Mailer.html">Mailer</a>                    </div>                </li>                            <li data-name="class:Stampie_MailerInterface" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="Stampie/MailerInterface.html">MailerInterface</a>                    </div>                </li>                            <li data-name="class:Stampie_Message" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="Stampie/Message.html">Message</a>                    </div>                </li>                            <li data-name="class:Stampie_MessageInterface" class="opened">                    <div style="padding-left:26px" class="hd leaf">                        <a href="Stampie/MessageInterface.html">MessageInterface</a>                    </div>                </li>                </ul></div>                </li>                </ul>';

    var searchTypeClasses = {
        'Namespace': 'label-default',
        'Class': 'label-info',
        'Interface': 'label-primary',
        'Trait': 'label-success',
        'Method': 'label-danger',
        '_': 'label-warning'
    };

    var searchIndex = [
                    
            {"type": "Namespace", "link": "Stampie.html", "name": "Stampie", "doc": "Namespace Stampie"},{"type": "Namespace", "link": "Stampie/Adapter.html", "name": "Stampie\\Adapter", "doc": "Namespace Stampie\\Adapter"},{"type": "Namespace", "link": "Stampie/Exception.html", "name": "Stampie\\Exception", "doc": "Namespace Stampie\\Exception"},{"type": "Namespace", "link": "Stampie/Mailer.html", "name": "Stampie\\Mailer", "doc": "Namespace Stampie\\Mailer"},{"type": "Namespace", "link": "Stampie/Message.html", "name": "Stampie\\Message", "doc": "Namespace Stampie\\Message"},{"type": "Namespace", "link": "Stampie/Util.html", "name": "Stampie\\Util", "doc": "Namespace Stampie\\Util"},
            {"type": "Interface", "fromName": "Stampie\\Adapter", "fromLink": "Stampie/Adapter.html", "link": "Stampie/Adapter/AdapterInterface.html", "name": "Stampie\\Adapter\\AdapterInterface", "doc": "&quot;Interface all adapters must implement.&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\Adapter\\AdapterInterface", "fromLink": "Stampie/Adapter/AdapterInterface.html", "link": "Stampie/Adapter/AdapterInterface.html#method_send", "name": "Stampie\\Adapter\\AdapterInterface::send", "doc": "&quot;&quot;"},
            
            {"type": "Interface", "fromName": "Stampie\\Adapter", "fromLink": "Stampie/Adapter.html", "link": "Stampie/Adapter/ResponseInterface.html", "name": "Stampie\\Adapter\\ResponseInterface", "doc": "&quot;Interface for returned content by adapters&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\Adapter\\ResponseInterface", "fromLink": "Stampie/Adapter/ResponseInterface.html", "link": "Stampie/Adapter/ResponseInterface.html#method_getStatusCode", "name": "Stampie\\Adapter\\ResponseInterface::getStatusCode", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Adapter\\ResponseInterface", "fromLink": "Stampie/Adapter/ResponseInterface.html", "link": "Stampie/Adapter/ResponseInterface.html#method_getContent", "name": "Stampie\\Adapter\\ResponseInterface::getContent", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Adapter\\ResponseInterface", "fromLink": "Stampie/Adapter/ResponseInterface.html", "link": "Stampie/Adapter/ResponseInterface.html#method_getStatusText", "name": "Stampie\\Adapter\\ResponseInterface::getStatusText", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Adapter\\ResponseInterface", "fromLink": "Stampie/Adapter/ResponseInterface.html", "link": "Stampie/Adapter/ResponseInterface.html#method_isSuccessful", "name": "Stampie\\Adapter\\ResponseInterface::isSuccessful", "doc": "&quot;&quot;"},
            
            {"type": "Interface", "fromName": "Stampie", "fromLink": "Stampie.html", "link": "Stampie/IdentityInterface.html", "name": "Stampie\\IdentityInterface", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\IdentityInterface", "fromLink": "Stampie/IdentityInterface.html", "link": "Stampie/IdentityInterface.html#method_getEmail", "name": "Stampie\\IdentityInterface::getEmail", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\IdentityInterface", "fromLink": "Stampie/IdentityInterface.html", "link": "Stampie/IdentityInterface.html#method_getName", "name": "Stampie\\IdentityInterface::getName", "doc": "&quot;&quot;"},
            
            {"type": "Interface", "fromName": "Stampie", "fromLink": "Stampie.html", "link": "Stampie/MailerInterface.html", "name": "Stampie\\MailerInterface", "doc": "&quot;Takes a MailerInterface and sends to an AdapterInterface.&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\MailerInterface", "fromLink": "Stampie/MailerInterface.html", "link": "Stampie/MailerInterface.html#method_setAdapter", "name": "Stampie\\MailerInterface::setAdapter", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\MailerInterface", "fromLink": "Stampie/MailerInterface.html", "link": "Stampie/MailerInterface.html#method_getAdapter", "name": "Stampie\\MailerInterface::getAdapter", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\MailerInterface", "fromLink": "Stampie/MailerInterface.html", "link": "Stampie/MailerInterface.html#method_setServerToken", "name": "Stampie\\MailerInterface::setServerToken", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\MailerInterface", "fromLink": "Stampie/MailerInterface.html", "link": "Stampie/MailerInterface.html#method_getServerToken", "name": "Stampie\\MailerInterface::getServerToken", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\MailerInterface", "fromLink": "Stampie/MailerInterface.html", "link": "Stampie/MailerInterface.html#method_send", "name": "Stampie\\MailerInterface::send", "doc": "&quot;&quot;"},
            
            {"type": "Interface", "fromName": "Stampie", "fromLink": "Stampie.html", "link": "Stampie/MessageInterface.html", "name": "Stampie\\MessageInterface", "doc": "&quot;Represents a simple Message. A Message is a storage of a message that\nwill be converted into an API call&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\MessageInterface", "fromLink": "Stampie/MessageInterface.html", "link": "Stampie/MessageInterface.html#method_getFrom", "name": "Stampie\\MessageInterface::getFrom", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\MessageInterface", "fromLink": "Stampie/MessageInterface.html", "link": "Stampie/MessageInterface.html#method_getTo", "name": "Stampie\\MessageInterface::getTo", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\MessageInterface", "fromLink": "Stampie/MessageInterface.html", "link": "Stampie/MessageInterface.html#method_getCc", "name": "Stampie\\MessageInterface::getCc", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\MessageInterface", "fromLink": "Stampie/MessageInterface.html", "link": "Stampie/MessageInterface.html#method_getBcc", "name": "Stampie\\MessageInterface::getBcc", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\MessageInterface", "fromLink": "Stampie/MessageInterface.html", "link": "Stampie/MessageInterface.html#method_getSubject", "name": "Stampie\\MessageInterface::getSubject", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\MessageInterface", "fromLink": "Stampie/MessageInterface.html", "link": "Stampie/MessageInterface.html#method_getReplyTo", "name": "Stampie\\MessageInterface::getReplyTo", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\MessageInterface", "fromLink": "Stampie/MessageInterface.html", "link": "Stampie/MessageInterface.html#method_getHeaders", "name": "Stampie\\MessageInterface::getHeaders", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\MessageInterface", "fromLink": "Stampie/MessageInterface.html", "link": "Stampie/MessageInterface.html#method_getHtml", "name": "Stampie\\MessageInterface::getHtml", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\MessageInterface", "fromLink": "Stampie/MessageInterface.html", "link": "Stampie/MessageInterface.html#method_getText", "name": "Stampie\\MessageInterface::getText", "doc": "&quot;&quot;"},
            
            {"type": "Interface", "fromName": "Stampie\\Message", "fromLink": "Stampie/Message.html", "link": "Stampie/Message/AttachmentsAwareInterface.html", "name": "Stampie\\Message\\AttachmentsAwareInterface", "doc": "&quot;Represents a Message that contains Attachments&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\Message\\AttachmentsAwareInterface", "fromLink": "Stampie/Message/AttachmentsAwareInterface.html", "link": "Stampie/Message/AttachmentsAwareInterface.html#method_getAttachments", "name": "Stampie\\Message\\AttachmentsAwareInterface::getAttachments", "doc": "&quot;&quot;"},
            
            {"type": "Interface", "fromName": "Stampie\\Message", "fromLink": "Stampie/Message.html", "link": "Stampie/Message/MetadataAwareInterface.html", "name": "Stampie\\Message\\MetadataAwareInterface", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\Message\\MetadataAwareInterface", "fromLink": "Stampie/Message/MetadataAwareInterface.html", "link": "Stampie/Message/MetadataAwareInterface.html#method_getMetadata", "name": "Stampie\\Message\\MetadataAwareInterface::getMetadata", "doc": "&quot;Gets the metadata attached to the message.&quot;"},
            
            {"type": "Interface", "fromName": "Stampie\\Message", "fromLink": "Stampie/Message.html", "link": "Stampie/Message/TaggableInterface.html", "name": "Stampie\\Message\\TaggableInterface", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\Message\\TaggableInterface", "fromLink": "Stampie/Message/TaggableInterface.html", "link": "Stampie/Message/TaggableInterface.html#method_getTag", "name": "Stampie\\Message\\TaggableInterface::getTag", "doc": "&quot;The tag(s) attached to the message.&quot;"},
            
            
            {"type": "Class", "fromName": "Stampie\\Adapter", "fromLink": "Stampie/Adapter.html", "link": "Stampie/Adapter/AdapterInterface.html", "name": "Stampie\\Adapter\\AdapterInterface", "doc": "&quot;Interface all adapters must implement.&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\Adapter\\AdapterInterface", "fromLink": "Stampie/Adapter/AdapterInterface.html", "link": "Stampie/Adapter/AdapterInterface.html#method_send", "name": "Stampie\\Adapter\\AdapterInterface::send", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Stampie\\Adapter", "fromLink": "Stampie/Adapter.html", "link": "Stampie/Adapter/Buzz.html", "name": "Stampie\\Adapter\\Buzz", "doc": "&quot;Adapter for Kriss Wallsmith&#039;s Buzz library&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\Adapter\\Buzz", "fromLink": "Stampie/Adapter/Buzz.html", "link": "Stampie/Adapter/Buzz.html#method___construct", "name": "Stampie\\Adapter\\Buzz::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Adapter\\Buzz", "fromLink": "Stampie/Adapter/Buzz.html", "link": "Stampie/Adapter/Buzz.html#method_getBrowser", "name": "Stampie\\Adapter\\Buzz::getBrowser", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Adapter\\Buzz", "fromLink": "Stampie/Adapter/Buzz.html", "link": "Stampie/Adapter/Buzz.html#method_send", "name": "Stampie\\Adapter\\Buzz::send", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Stampie\\Adapter", "fromLink": "Stampie/Adapter.html", "link": "Stampie/Adapter/Guzzle.html", "name": "Stampie\\Adapter\\Guzzle", "doc": "&quot;Guzzle Adapter (guzzlephp.org)&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\Adapter\\Guzzle", "fromLink": "Stampie/Adapter/Guzzle.html", "link": "Stampie/Adapter/Guzzle.html#method___construct", "name": "Stampie\\Adapter\\Guzzle::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Adapter\\Guzzle", "fromLink": "Stampie/Adapter/Guzzle.html", "link": "Stampie/Adapter/Guzzle.html#method_getClient", "name": "Stampie\\Adapter\\Guzzle::getClient", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Adapter\\Guzzle", "fromLink": "Stampie/Adapter/Guzzle.html", "link": "Stampie/Adapter/Guzzle.html#method_send", "name": "Stampie\\Adapter\\Guzzle::send", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Stampie\\Adapter", "fromLink": "Stampie/Adapter.html", "link": "Stampie/Adapter/NoopAdapter.html", "name": "Stampie\\Adapter\\NoopAdapter", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\Adapter\\NoopAdapter", "fromLink": "Stampie/Adapter/NoopAdapter.html", "link": "Stampie/Adapter/NoopAdapter.html#method_send", "name": "Stampie\\Adapter\\NoopAdapter::send", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Stampie\\Adapter", "fromLink": "Stampie/Adapter.html", "link": "Stampie/Adapter/Response.html", "name": "Stampie\\Adapter\\Response", "doc": "&quot;Immutable implementation of ResponseInterface&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\Adapter\\Response", "fromLink": "Stampie/Adapter/Response.html", "link": "Stampie/Adapter/Response.html#method___construct", "name": "Stampie\\Adapter\\Response::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Adapter\\Response", "fromLink": "Stampie/Adapter/Response.html", "link": "Stampie/Adapter/Response.html#method_getStatusCode", "name": "Stampie\\Adapter\\Response::getStatusCode", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Adapter\\Response", "fromLink": "Stampie/Adapter/Response.html", "link": "Stampie/Adapter/Response.html#method_getContent", "name": "Stampie\\Adapter\\Response::getContent", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Adapter\\Response", "fromLink": "Stampie/Adapter/Response.html", "link": "Stampie/Adapter/Response.html#method_isSuccessful", "name": "Stampie\\Adapter\\Response::isSuccessful", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Adapter\\Response", "fromLink": "Stampie/Adapter/Response.html", "link": "Stampie/Adapter/Response.html#method_getStatusText", "name": "Stampie\\Adapter\\Response::getStatusText", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Stampie\\Adapter", "fromLink": "Stampie/Adapter.html", "link": "Stampie/Adapter/ResponseInterface.html", "name": "Stampie\\Adapter\\ResponseInterface", "doc": "&quot;Interface for returned content by adapters&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\Adapter\\ResponseInterface", "fromLink": "Stampie/Adapter/ResponseInterface.html", "link": "Stampie/Adapter/ResponseInterface.html#method_getStatusCode", "name": "Stampie\\Adapter\\ResponseInterface::getStatusCode", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Adapter\\ResponseInterface", "fromLink": "Stampie/Adapter/ResponseInterface.html", "link": "Stampie/Adapter/ResponseInterface.html#method_getContent", "name": "Stampie\\Adapter\\ResponseInterface::getContent", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Adapter\\ResponseInterface", "fromLink": "Stampie/Adapter/ResponseInterface.html", "link": "Stampie/Adapter/ResponseInterface.html#method_getStatusText", "name": "Stampie\\Adapter\\ResponseInterface::getStatusText", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Adapter\\ResponseInterface", "fromLink": "Stampie/Adapter/ResponseInterface.html", "link": "Stampie/Adapter/ResponseInterface.html#method_isSuccessful", "name": "Stampie\\Adapter\\ResponseInterface::isSuccessful", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Stampie", "fromLink": "Stampie.html", "link": "Stampie/Attachment.html", "name": "Stampie\\Attachment", "doc": "&quot;An Attachment is a container for a file that will be included with a Message.&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\Attachment", "fromLink": "Stampie/Attachment.html", "link": "Stampie/Attachment.html#method___construct", "name": "Stampie\\Attachment::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Attachment", "fromLink": "Stampie/Attachment.html", "link": "Stampie/Attachment.html#method_isValidFile", "name": "Stampie\\Attachment::isValidFile", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Attachment", "fromLink": "Stampie/Attachment.html", "link": "Stampie/Attachment.html#method_determineFileType", "name": "Stampie\\Attachment::determineFileType", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Attachment", "fromLink": "Stampie/Attachment.html", "link": "Stampie/Attachment.html#method_getPath", "name": "Stampie\\Attachment::getPath", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Attachment", "fromLink": "Stampie/Attachment.html", "link": "Stampie/Attachment.html#method_getName", "name": "Stampie\\Attachment::getName", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Attachment", "fromLink": "Stampie/Attachment.html", "link": "Stampie/Attachment.html#method_getType", "name": "Stampie\\Attachment::getType", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Attachment", "fromLink": "Stampie/Attachment.html", "link": "Stampie/Attachment.html#method_getId", "name": "Stampie\\Attachment::getId", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Stampie\\Exception", "fromLink": "Stampie/Exception.html", "link": "Stampie/Exception/ApiException.html", "name": "Stampie\\Exception\\ApiException", "doc": "&quot;SubException&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\Exception\\ApiException", "fromLink": "Stampie/Exception/ApiException.html", "link": "Stampie/Exception/ApiException.html#method___construct", "name": "Stampie\\Exception\\ApiException::__construct", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Stampie\\Exception", "fromLink": "Stampie/Exception.html", "link": "Stampie/Exception/HttpException.html", "name": "Stampie\\Exception\\HttpException", "doc": "&quot;Exception thrown for all HTTP Error codes where the Api&#039;s doesn&#039;t themselves provide an error\nmessage.&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\Exception\\HttpException", "fromLink": "Stampie/Exception/HttpException.html", "link": "Stampie/Exception/HttpException.html#method___construct", "name": "Stampie\\Exception\\HttpException::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Exception\\HttpException", "fromLink": "Stampie/Exception/HttpException.html", "link": "Stampie/Exception/HttpException.html#method_getStatusCode", "name": "Stampie\\Exception\\HttpException::getStatusCode", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Stampie", "fromLink": "Stampie.html", "link": "Stampie/Identity.html", "name": "Stampie\\Identity", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\Identity", "fromLink": "Stampie/Identity.html", "link": "Stampie/Identity.html#method___construct", "name": "Stampie\\Identity::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Identity", "fromLink": "Stampie/Identity.html", "link": "Stampie/Identity.html#method_setEmail", "name": "Stampie\\Identity::setEmail", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Identity", "fromLink": "Stampie/Identity.html", "link": "Stampie/Identity.html#method_getEmail", "name": "Stampie\\Identity::getEmail", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Identity", "fromLink": "Stampie/Identity.html", "link": "Stampie/Identity.html#method_setName", "name": "Stampie\\Identity::setName", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Identity", "fromLink": "Stampie/Identity.html", "link": "Stampie/Identity.html#method_getName", "name": "Stampie\\Identity::getName", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Stampie", "fromLink": "Stampie.html", "link": "Stampie/IdentityInterface.html", "name": "Stampie\\IdentityInterface", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\IdentityInterface", "fromLink": "Stampie/IdentityInterface.html", "link": "Stampie/IdentityInterface.html#method_getEmail", "name": "Stampie\\IdentityInterface::getEmail", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\IdentityInterface", "fromLink": "Stampie/IdentityInterface.html", "link": "Stampie/IdentityInterface.html#method_getName", "name": "Stampie\\IdentityInterface::getName", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Stampie", "fromLink": "Stampie.html", "link": "Stampie/Mailer.html", "name": "Stampie\\Mailer", "doc": "&quot;Minimal implementation of a MailerInterface&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\Mailer", "fromLink": "Stampie/Mailer.html", "link": "Stampie/Mailer.html#method___construct", "name": "Stampie\\Mailer::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer", "fromLink": "Stampie/Mailer.html", "link": "Stampie/Mailer.html#method_setAdapter", "name": "Stampie\\Mailer::setAdapter", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer", "fromLink": "Stampie/Mailer.html", "link": "Stampie/Mailer.html#method_getAdapter", "name": "Stampie\\Mailer::getAdapter", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer", "fromLink": "Stampie/Mailer.html", "link": "Stampie/Mailer.html#method_setServerToken", "name": "Stampie\\Mailer::setServerToken", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer", "fromLink": "Stampie/Mailer.html", "link": "Stampie/Mailer.html#method_getServerToken", "name": "Stampie\\Mailer::getServerToken", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer", "fromLink": "Stampie/Mailer.html", "link": "Stampie/Mailer.html#method_send", "name": "Stampie\\Mailer::send", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer", "fromLink": "Stampie/Mailer.html", "link": "Stampie/Mailer.html#method_getHeaders", "name": "Stampie\\Mailer::getHeaders", "doc": "&quot;Return a key -&gt; value array of headers&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer", "fromLink": "Stampie/Mailer.html", "link": "Stampie/Mailer.html#method_getFiles", "name": "Stampie\\Mailer::getFiles", "doc": "&quot;Return an key -&gt; value array of files&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer", "fromLink": "Stampie/Mailer.html", "link": "Stampie/Mailer.html#method_getEndpoint", "name": "Stampie\\Mailer::getEndpoint", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer", "fromLink": "Stampie/Mailer.html", "link": "Stampie/Mailer.html#method_format", "name": "Stampie\\Mailer::format", "doc": "&quot;Return a a string formatted for the correct Mailer endpoint.&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer", "fromLink": "Stampie/Mailer.html", "link": "Stampie/Mailer.html#method_handle", "name": "Stampie\\Mailer::handle", "doc": "&quot;If a Response is not successful it will be passed to this method\neach Mailer should then throw an HttpException with an optional\nApiException to help identify the problem.&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer", "fromLink": "Stampie/Mailer.html", "link": "Stampie/Mailer.html#method_normalizeIdentity", "name": "Stampie\\Mailer::normalizeIdentity", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer", "fromLink": "Stampie/Mailer.html", "link": "Stampie/Mailer.html#method_normalizeIdentities", "name": "Stampie\\Mailer::normalizeIdentities", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer", "fromLink": "Stampie/Mailer.html", "link": "Stampie/Mailer.html#method_buildIdentityString", "name": "Stampie\\Mailer::buildIdentityString", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Stampie", "fromLink": "Stampie.html", "link": "Stampie/MailerInterface.html", "name": "Stampie\\MailerInterface", "doc": "&quot;Takes a MailerInterface and sends to an AdapterInterface.&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\MailerInterface", "fromLink": "Stampie/MailerInterface.html", "link": "Stampie/MailerInterface.html#method_setAdapter", "name": "Stampie\\MailerInterface::setAdapter", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\MailerInterface", "fromLink": "Stampie/MailerInterface.html", "link": "Stampie/MailerInterface.html#method_getAdapter", "name": "Stampie\\MailerInterface::getAdapter", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\MailerInterface", "fromLink": "Stampie/MailerInterface.html", "link": "Stampie/MailerInterface.html#method_setServerToken", "name": "Stampie\\MailerInterface::setServerToken", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\MailerInterface", "fromLink": "Stampie/MailerInterface.html", "link": "Stampie/MailerInterface.html#method_getServerToken", "name": "Stampie\\MailerInterface::getServerToken", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\MailerInterface", "fromLink": "Stampie/MailerInterface.html", "link": "Stampie/MailerInterface.html#method_send", "name": "Stampie\\MailerInterface::send", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Stampie\\Mailer", "fromLink": "Stampie/Mailer.html", "link": "Stampie/Mailer/MailGun.html", "name": "Stampie\\Mailer\\MailGun", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\Mailer\\MailGun", "fromLink": "Stampie/Mailer/MailGun.html", "link": "Stampie/Mailer/MailGun.html#method_getEndpoint", "name": "Stampie\\Mailer\\MailGun::getEndpoint", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer\\MailGun", "fromLink": "Stampie/Mailer/MailGun.html", "link": "Stampie/Mailer/MailGun.html#method_setServerToken", "name": "Stampie\\Mailer\\MailGun::setServerToken", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer\\MailGun", "fromLink": "Stampie/Mailer/MailGun.html", "link": "Stampie/Mailer/MailGun.html#method_getHeaders", "name": "Stampie\\Mailer\\MailGun::getHeaders", "doc": "&quot;Return a key -&gt; value array of headers&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer\\MailGun", "fromLink": "Stampie/Mailer/MailGun.html", "link": "Stampie/Mailer/MailGun.html#method_getFiles", "name": "Stampie\\Mailer\\MailGun::getFiles", "doc": "&quot;Return an key -&gt; value array of files&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer\\MailGun", "fromLink": "Stampie/Mailer/MailGun.html", "link": "Stampie/Mailer/MailGun.html#method_format", "name": "Stampie\\Mailer\\MailGun::format", "doc": "&quot;Return a a string formatted for the correct Mailer endpoint.&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer\\MailGun", "fromLink": "Stampie/Mailer/MailGun.html", "link": "Stampie/Mailer/MailGun.html#method_handle", "name": "Stampie\\Mailer\\MailGun::handle", "doc": "&quot;If a Response is not successful it will be passed to this method\neach Mailer should then throw an HttpException with an optional\nApiException to help identify the problem.&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer\\MailGun", "fromLink": "Stampie/Mailer/MailGun.html", "link": "Stampie/Mailer/MailGun.html#method_processAttachments", "name": "Stampie\\Mailer\\MailGun::processAttachments", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Stampie\\Mailer", "fromLink": "Stampie/Mailer.html", "link": "Stampie/Mailer/Mandrill.html", "name": "Stampie\\Mailer\\Mandrill", "doc": "&quot;Sends emails to Mandrill server&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\Mailer\\Mandrill", "fromLink": "Stampie/Mailer/Mandrill.html", "link": "Stampie/Mailer/Mandrill.html#method_getEndpoint", "name": "Stampie\\Mailer\\Mandrill::getEndpoint", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer\\Mandrill", "fromLink": "Stampie/Mailer/Mandrill.html", "link": "Stampie/Mailer/Mandrill.html#method_getHeaders", "name": "Stampie\\Mailer\\Mandrill::getHeaders", "doc": "&quot;Return a key -&gt; value array of headers&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer\\Mandrill", "fromLink": "Stampie/Mailer/Mandrill.html", "link": "Stampie/Mailer/Mandrill.html#method_format", "name": "Stampie\\Mailer\\Mandrill::format", "doc": "&quot;Return a a string formatted for the correct Mailer endpoint.&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer\\Mandrill", "fromLink": "Stampie/Mailer/Mandrill.html", "link": "Stampie/Mailer/Mandrill.html#method_handle", "name": "Stampie\\Mailer\\Mandrill::handle", "doc": "&quot;If a Response is not successful it will be passed to this method\neach Mailer should then throw an HttpException with an optional\nApiException to help identify the problem.&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer\\Mandrill", "fromLink": "Stampie/Mailer/Mandrill.html", "link": "Stampie/Mailer/Mandrill.html#method_processAttachments", "name": "Stampie\\Mailer\\Mandrill::processAttachments", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer\\Mandrill", "fromLink": "Stampie/Mailer/Mandrill.html", "link": "Stampie/Mailer/Mandrill.html#method_getAttachmentContent", "name": "Stampie\\Mailer\\Mandrill::getAttachmentContent", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Stampie\\Mailer", "fromLink": "Stampie/Mailer.html", "link": "Stampie/Mailer/Postmark.html", "name": "Stampie\\Mailer\\Postmark", "doc": "&quot;Sends emails to Postmark server&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\Mailer\\Postmark", "fromLink": "Stampie/Mailer/Postmark.html", "link": "Stampie/Mailer/Postmark.html#method_getEndpoint", "name": "Stampie\\Mailer\\Postmark::getEndpoint", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer\\Postmark", "fromLink": "Stampie/Mailer/Postmark.html", "link": "Stampie/Mailer/Postmark.html#method_handle", "name": "Stampie\\Mailer\\Postmark::handle", "doc": "&quot;If a Response is not successful it will be passed to this method\neach Mailer should then throw an HttpException with an optional\nApiException to help identify the problem.&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer\\Postmark", "fromLink": "Stampie/Mailer/Postmark.html", "link": "Stampie/Mailer/Postmark.html#method_getHeaders", "name": "Stampie\\Mailer\\Postmark::getHeaders", "doc": "&quot;Return a key -&gt; value array of headers&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer\\Postmark", "fromLink": "Stampie/Mailer/Postmark.html", "link": "Stampie/Mailer/Postmark.html#method_format", "name": "Stampie\\Mailer\\Postmark::format", "doc": "&quot;Return a a string formatted for the correct Mailer endpoint.&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer\\Postmark", "fromLink": "Stampie/Mailer/Postmark.html", "link": "Stampie/Mailer/Postmark.html#method_getAttachmentContent", "name": "Stampie\\Mailer\\Postmark::getAttachmentContent", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer\\Postmark", "fromLink": "Stampie/Mailer/Postmark.html", "link": "Stampie/Mailer/Postmark.html#method_processAttachments", "name": "Stampie\\Mailer\\Postmark::processAttachments", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Stampie\\Mailer", "fromLink": "Stampie/Mailer.html", "link": "Stampie/Mailer/SendGrid.html", "name": "Stampie\\Mailer\\SendGrid", "doc": "&quot;Mailer to be used with SendGrid Web API&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\Mailer\\SendGrid", "fromLink": "Stampie/Mailer/SendGrid.html", "link": "Stampie/Mailer/SendGrid.html#method_getEndpoint", "name": "Stampie\\Mailer\\SendGrid::getEndpoint", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer\\SendGrid", "fromLink": "Stampie/Mailer/SendGrid.html", "link": "Stampie/Mailer/SendGrid.html#method_setServerToken", "name": "Stampie\\Mailer\\SendGrid::setServerToken", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer\\SendGrid", "fromLink": "Stampie/Mailer/SendGrid.html", "link": "Stampie/Mailer/SendGrid.html#method_getFiles", "name": "Stampie\\Mailer\\SendGrid::getFiles", "doc": "&quot;Return an key -&gt; value array of files&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer\\SendGrid", "fromLink": "Stampie/Mailer/SendGrid.html", "link": "Stampie/Mailer/SendGrid.html#method_handle", "name": "Stampie\\Mailer\\SendGrid::handle", "doc": "&quot;If a Response is not successful it will be passed to this method\neach Mailer should then throw an HttpException with an optional\nApiException to help identify the problem.&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer\\SendGrid", "fromLink": "Stampie/Mailer/SendGrid.html", "link": "Stampie/Mailer/SendGrid.html#method_format", "name": "Stampie\\Mailer\\SendGrid::format", "doc": "&quot;Return a a string formatted for the correct Mailer endpoint.&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Mailer\\SendGrid", "fromLink": "Stampie/Mailer/SendGrid.html", "link": "Stampie/Mailer/SendGrid.html#method_processAttachments", "name": "Stampie\\Mailer\\SendGrid::processAttachments", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Stampie", "fromLink": "Stampie.html", "link": "Stampie/Message.html", "name": "Stampie\\Message", "doc": "&quot;Implementation of MessageInterface where only getFrom() and getSubject()\nis required to be implemented.&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\Message", "fromLink": "Stampie/Message.html", "link": "Stampie/Message.html#method___construct", "name": "Stampie\\Message::__construct", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Message", "fromLink": "Stampie/Message.html", "link": "Stampie/Message.html#method_getTo", "name": "Stampie\\Message::getTo", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Message", "fromLink": "Stampie/Message.html", "link": "Stampie/Message.html#method_setHtml", "name": "Stampie\\Message::setHtml", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Message", "fromLink": "Stampie/Message.html", "link": "Stampie/Message.html#method_setText", "name": "Stampie\\Message::setText", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Message", "fromLink": "Stampie/Message.html", "link": "Stampie/Message.html#method_getHtml", "name": "Stampie\\Message::getHtml", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Message", "fromLink": "Stampie/Message.html", "link": "Stampie/Message.html#method_getText", "name": "Stampie\\Message::getText", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Message", "fromLink": "Stampie/Message.html", "link": "Stampie/Message.html#method_getHeaders", "name": "Stampie\\Message::getHeaders", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Message", "fromLink": "Stampie/Message.html", "link": "Stampie/Message.html#method_getReplyTo", "name": "Stampie\\Message::getReplyTo", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Message", "fromLink": "Stampie/Message.html", "link": "Stampie/Message.html#method_getCc", "name": "Stampie\\Message::getCc", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Message", "fromLink": "Stampie/Message.html", "link": "Stampie/Message.html#method_getBcc", "name": "Stampie\\Message::getBcc", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Stampie", "fromLink": "Stampie.html", "link": "Stampie/MessageInterface.html", "name": "Stampie\\MessageInterface", "doc": "&quot;Represents a simple Message. A Message is a storage of a message that\nwill be converted into an API call&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\MessageInterface", "fromLink": "Stampie/MessageInterface.html", "link": "Stampie/MessageInterface.html#method_getFrom", "name": "Stampie\\MessageInterface::getFrom", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\MessageInterface", "fromLink": "Stampie/MessageInterface.html", "link": "Stampie/MessageInterface.html#method_getTo", "name": "Stampie\\MessageInterface::getTo", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\MessageInterface", "fromLink": "Stampie/MessageInterface.html", "link": "Stampie/MessageInterface.html#method_getCc", "name": "Stampie\\MessageInterface::getCc", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\MessageInterface", "fromLink": "Stampie/MessageInterface.html", "link": "Stampie/MessageInterface.html#method_getBcc", "name": "Stampie\\MessageInterface::getBcc", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\MessageInterface", "fromLink": "Stampie/MessageInterface.html", "link": "Stampie/MessageInterface.html#method_getSubject", "name": "Stampie\\MessageInterface::getSubject", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\MessageInterface", "fromLink": "Stampie/MessageInterface.html", "link": "Stampie/MessageInterface.html#method_getReplyTo", "name": "Stampie\\MessageInterface::getReplyTo", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\MessageInterface", "fromLink": "Stampie/MessageInterface.html", "link": "Stampie/MessageInterface.html#method_getHeaders", "name": "Stampie\\MessageInterface::getHeaders", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\MessageInterface", "fromLink": "Stampie/MessageInterface.html", "link": "Stampie/MessageInterface.html#method_getHtml", "name": "Stampie\\MessageInterface::getHtml", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\MessageInterface", "fromLink": "Stampie/MessageInterface.html", "link": "Stampie/MessageInterface.html#method_getText", "name": "Stampie\\MessageInterface::getText", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Stampie\\Message", "fromLink": "Stampie/Message.html", "link": "Stampie/Message/AttachmentsAwareInterface.html", "name": "Stampie\\Message\\AttachmentsAwareInterface", "doc": "&quot;Represents a Message that contains Attachments&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\Message\\AttachmentsAwareInterface", "fromLink": "Stampie/Message/AttachmentsAwareInterface.html", "link": "Stampie/Message/AttachmentsAwareInterface.html#method_getAttachments", "name": "Stampie\\Message\\AttachmentsAwareInterface::getAttachments", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Stampie\\Message", "fromLink": "Stampie/Message.html", "link": "Stampie/Message/MetadataAwareInterface.html", "name": "Stampie\\Message\\MetadataAwareInterface", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\Message\\MetadataAwareInterface", "fromLink": "Stampie/Message/MetadataAwareInterface.html", "link": "Stampie/Message/MetadataAwareInterface.html#method_getMetadata", "name": "Stampie\\Message\\MetadataAwareInterface::getMetadata", "doc": "&quot;Gets the metadata attached to the message.&quot;"},
            
            {"type": "Class", "fromName": "Stampie\\Message", "fromLink": "Stampie/Message.html", "link": "Stampie/Message/TaggableInterface.html", "name": "Stampie\\Message\\TaggableInterface", "doc": "&quot;&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\Message\\TaggableInterface", "fromLink": "Stampie/Message/TaggableInterface.html", "link": "Stampie/Message/TaggableInterface.html#method_getTag", "name": "Stampie\\Message\\TaggableInterface::getTag", "doc": "&quot;The tag(s) attached to the message.&quot;"},
            
            {"type": "Class", "fromName": "Stampie\\Util", "fromLink": "Stampie/Util.html", "link": "Stampie/Util/AttachmentUtils.html", "name": "Stampie\\Util\\AttachmentUtils", "doc": "&quot;Stampie Attachment utility functions&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\Util\\AttachmentUtils", "fromLink": "Stampie/Util/AttachmentUtils.html", "link": "Stampie/Util/AttachmentUtils.html#method_processAttachments", "name": "Stampie\\Util\\AttachmentUtils::processAttachments", "doc": "&quot;Applies a function to each attachment, and finds a unique name for any conflicting names&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Util\\AttachmentUtils", "fromLink": "Stampie/Util/AttachmentUtils.html", "link": "Stampie/Util/AttachmentUtils.html#method_findUniqueName", "name": "Stampie\\Util\\AttachmentUtils::findUniqueName", "doc": "&quot;&quot;"},
            
            {"type": "Class", "fromName": "Stampie\\Util", "fromLink": "Stampie/Util.html", "link": "Stampie/Util/IdentityUtils.html", "name": "Stampie\\Util\\IdentityUtils", "doc": "&quot;Stampie Identity utility functions&quot;"},
                                                        {"type": "Method", "fromName": "Stampie\\Util\\IdentityUtils", "fromLink": "Stampie/Util/IdentityUtils.html", "link": "Stampie/Util/IdentityUtils.html#method_normalizeIdentity", "name": "Stampie\\Util\\IdentityUtils::normalizeIdentity", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Util\\IdentityUtils", "fromLink": "Stampie/Util/IdentityUtils.html", "link": "Stampie/Util/IdentityUtils.html#method_normalizeIdentities", "name": "Stampie\\Util\\IdentityUtils::normalizeIdentities", "doc": "&quot;&quot;"},
                    {"type": "Method", "fromName": "Stampie\\Util\\IdentityUtils", "fromLink": "Stampie/Util/IdentityUtils.html", "link": "Stampie/Util/IdentityUtils.html#method_buildIdentityString", "name": "Stampie\\Util\\IdentityUtils::buildIdentityString", "doc": "&quot;&quot;"},
            
            
                                        // Fix trailing commas in the index
        {}
    ];

    /** Tokenizes strings by namespaces and functions */
    function tokenizer(term) {
        if (!term) {
            return [];
        }

        var tokens = [term];
        var meth = term.indexOf('::');

        // Split tokens into methods if "::" is found.
        if (meth > -1) {
            tokens.push(term.substr(meth + 2));
            term = term.substr(0, meth - 2);
        }

        // Split by namespace or fake namespace.
        if (term.indexOf('\\') > -1) {
            tokens = tokens.concat(term.split('\\'));
        } else if (term.indexOf('_') > 0) {
            tokens = tokens.concat(term.split('_'));
        }

        // Merge in splitting the string by case and return
        tokens = tokens.concat(term.match(/(([A-Z]?[^A-Z]*)|([a-z]?[^a-z]*))/g).slice(0,-1));

        return tokens;
    };

    root.Sami = {
        /**
         * Cleans the provided term. If no term is provided, then one is
         * grabbed from the query string "search" parameter.
         */
        cleanSearchTerm: function(term) {
            // Grab from the query string
            if (typeof term === 'undefined') {
                var name = 'search';
                var regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
                var results = regex.exec(location.search);
                if (results === null) {
                    return null;
                }
                term = decodeURIComponent(results[1].replace(/\+/g, " "));
            }

            return term.replace(/<(?:.|\n)*?>/gm, '');
        },

        /** Searches through the index for a given term */
        search: function(term) {
            // Create a new search index if needed
            if (!bhIndex) {
                bhIndex = new Bloodhound({
                    limit: 500,
                    local: searchIndex,
                    datumTokenizer: function (d) {
                        return tokenizer(d.name);
                    },
                    queryTokenizer: Bloodhound.tokenizers.whitespace
                });
                bhIndex.initialize();
            }

            results = [];
            bhIndex.get(term, function(matches) {
                results = matches;
            });

            if (!rootPath) {
                return results;
            }

            // Fix the element links based on the current page depth.
            return $.map(results, function(ele) {
                if (ele.link.indexOf('..') > -1) {
                    return ele;
                }
                ele.link = rootPath + ele.link;
                if (ele.fromLink) {
                    ele.fromLink = rootPath + ele.fromLink;
                }
                return ele;
            });
        },

        /** Get a search class for a specific type */
        getSearchClass: function(type) {
            return searchTypeClasses[type] || searchTypeClasses['_'];
        },

        /** Add the left-nav tree to the site */
        injectApiTree: function(ele) {
            ele.html(treeHtml);
        }
    };

    $(function() {
        // Modify the HTML to work correctly based on the current depth
        rootPath = $('body').attr('data-root-path');
        treeHtml = treeHtml.replace(/href="/g, 'href="' + rootPath);
        Sami.injectApiTree($('#api-tree'));
    });

    return root.Sami;
})(window);

$(function() {

    // Enable the version switcher
    $('#version-switcher').change(function() {
        window.location = $(this).val()
    });

    
        // Toggle left-nav divs on click
        $('#api-tree .hd span').click(function() {
            $(this).parent().parent().toggleClass('opened');
        });

        // Expand the parent namespaces of the current page.
        var expected = $('body').attr('data-name');

        if (expected) {
            // Open the currently selected node and its parents.
            var container = $('#api-tree');
            var node = $('#api-tree li[data-name="' + expected + '"]');
            // Node might not be found when simulating namespaces
            if (node.length > 0) {
                node.addClass('active').addClass('opened');
                node.parents('li').addClass('opened');
                var scrollPos = node.offset().top - container.offset().top + container.scrollTop();
                // Position the item nearer to the top of the screen.
                scrollPos -= 200;
                container.scrollTop(scrollPos);
            }
        }

    
    
        var form = $('#search-form .typeahead');
        form.typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            name: 'search',
            displayKey: 'name',
            source: function (q, cb) {
                cb(Sami.search(q));
            }
        });

        // The selection is direct-linked when the user selects a suggestion.
        form.on('typeahead:selected', function(e, suggestion) {
            window.location = suggestion.link;
        });

        // The form is submitted when the user hits enter.
        form.keypress(function (e) {
            if (e.which == 13) {
                $('#search-form').submit();
                return true;
            }
        });

    
});


