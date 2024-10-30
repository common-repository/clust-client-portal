const { registerBlockType, RichText } = wp.blocks;

/* This section of the code registers a new block, sets an icon and a category, and indicates what type of fields it'll include. */
(function (blocks, element, components, compose, editor) {

    //table containing portal options
    var element = Array(3);
    element[1] = React.createElement("option", { value: 0, disabled: true }, "Choose a portal"); // frist case title:Choose a portal

    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    // Create React Element to fill the select in the Page Editor
    function makeElement() {
        var api_token = getCookie("CL-apiToken");
        var count = null;

        const userAction = async () => {
            const response = await fetch('https://clustdoc.com/api/portals?api_token=' + api_token);
            const myJson = await response.json();
            count = myJson.data.length;
            for (var i = 0; i < count; i++) {
                var name = myJson.data[i].title;
                var value = myJson.data[i].public_url + "?embedded=1";
                element[i + 2] = React.createElement("option", { value: value }, name)
            }
        }
        userAction();
        return element;
    }

    // Registering a new block type
    wp.blocks.registerBlockType('myblock/call-to-action', {
        title: 'Clustdoc Portal',
        icon: 'embed-generic',
        category: 'common',
        attributes: {
            content: { type: 'string' },
            value: { type: 'string' },
        },
        // Definition of the Block Page Editor side
        edit: function (props) {
            function updateContent(event) {
                props.setAttributes({ content: event.target.value })
            }
            var element = makeElement();
            return React.createElement("div", { style: { backgroundColor: "#8B8B961A", border: "1px solid", padding: "0px 0px 20px 100px" } },
                React.createElement("h4", null, "Select the portal to insert : "),
                React.createElement("select", { type: "text", value: props.attributes.content, onChange: updateContent }, element));
        },
        // Definition of the Block Page side
        save: function (props) {
            return React.createElement(
                "iframe", { src: props.attributes.content, style: { width: "100%", height: "800px" } }, "");
        }
    });
})(window.wp.blocks, window.wp.element, window.wp.components, window.wp.compose, window.wp.editor);
