<script id="projectListItemTmpl" type="text/template">
    <!-- <div> -->
    <a href="#<%= uid %>" id="link_<%= uid %>" data-lat="<%= p.p_lat %>" data-lng="<%= p.p_lng %>">
        <article>
            <div class="image">
                <span class="number"><%= i %></span>
                <img src="<%= p.p_image_small %>" alt="<%= p.p_title %>" width="50" height="50" />
                <span class="<%= p.p_stage_class %>"><%= p.p_stage %></span>
            </div>
            <div class="content">
                <h2><%= p.p_title %></h2>
                <span class="price"><%= p.p_budget %></span>
                <span class="type <%= p.p_sector_class %>"><%= p.p_sector %></span>
            </div>
        </article>
    </a>
    <!-- </div> -->
</script>

<script id="expertListItemTmpl" type="text/template">
    <a href="#<%= uid %>" id="link_<%= uid %>" data-lat="<%= p.p_lat %>" data-lng="<%= p.p_lng %>" data-index="<%= i %>">
        <article>
            <div class="image">
                <span class="number"><%= i %></span>
                <img src="<%= p.p_image_small %>" alt="<%= p.p_title %>" width="50" height="50" />
            </div>
            <div class="content">
                <h2><%= p.p_name %></h2>
                <span class="title"><% if (p.p_title) { print(p.p_title + ', '); } %><%= p.p_organization %></span>
            </div>
        </article>
    </a>
</script>

<script id="projectPopupTmpl" type="text/template">
    <div class="marker <%= o.p_sector_class %>">
        <span><%= i %></span>
        <!-- <span class="budget <%= o.p_sector_class %>"><%= o.p_budget %></span> -->
    </div>
    <div class="open_project">
        <button type="button" class="close">X</button>
        <div class="row">
            <div class="image">
                <span class="number"><%= i %></span>
                <img src="<%= o.p_image_big %>" alt="Alt Text">
                <span class="<%= o.p_stage_class %>">Stage <%= o.p_stage_class %></span>
        <span class="stage"> Stage
          <strong><%= o.p_stage %></strong>
        </span>
            </div>
            <div class="content">
                <h2><%= o.p_title %></h2>
                <span class="location"><%= o.p_location %></span>
                <% if(o.p_date_start !== "" && o.p_date_end !== "") { %>
                <span class="date-range"><%= o.p_date_start %> to <%= o.p_date_end %></span>
                <% } %>
                <%if (o.p_date_start !== "" && o.p_date_end === "") { %>
                <span class="date-range"><em>Starts</em> <%= o.p_date_start %></span>
                <% } %>
                <% if (o.p_date_start === "" && o.p_date_end !== "") {%>
                <span class="date-range"><em>Ends</em> <%= o.p_date_end %></span>
                <% } %>

                <% if( o.p_sponsor !== ''){%>
          <span class="sponsor"> <strong>Sponsored By</strong>
            <%= o.p_sponsor %>
          </span>
                <% } %>
                <% if( o.p_developer !== ''){%>
          <span class="sponsor"> <strong>Developed By</strong>
            <%= o.p_developer %>
          </span>
                <% } %>
            </div>
        </div>
        <div class="row type <%= o.p_sector_class %>">
            <span class="price"><%= o.p_budget %></span>
            <span class="sector"><%= o.p_sector %></span>
            <p><%= o.p_subsector %>&nbsp;</p>
        </div>
        <div class="row view_project">
            <a href="<%= o.p_link %>">View Project</a>
        </div>
    </div>
</script>

<script id="expertPopupTmpl" type="text/template">
    <div class="marker">
        <span class="number"><%= i %></span>
        <img src="<%= o.p_image_circle %>" alt="<%= o.p_name %>">
    </div>
    <div class="open_expert">
        <button type="button" class="close">X</button>
        <div class="row">
            <div class="image">
                <span class="number"><%= i %></span>
                <img src="<%= o.p_image_big %>" alt="<%= o.p_name %>">
            </div>
            <div class="content">
                <h2><%= o.p_name %></h2>
                <span class="title"><% if (o.p_title) { print(o.p_title + ','); } %><% if (o.p_organization) { print(' ' + o.p_organization); } %></span>
                <% if (o.p_budget !== "$0MM") { %>
                <strong class="c_info">Company Info</strong>
                <span class="dollars"><%= o.p_budget %></span>
                <% } %>
                <% if (o.p_discipline !== "") {%>
                <p>
                    <strong>Discipline</strong>
                    <%= o.p_discipline %>
                </p>
                <% } %>
            </div>
        </div>
        <% if( o.p_sectors !== '{}'){ %>
        <% var data = jQuery.parseJSON(o.p_sectors) %>
        <div class="row type">
            <% $.each(data,function(i,v){%>
            <span class="<%= i %>"><%= v %></span>
            <% }); %>
        </div>
        <% } %>
        <div class="row view_expert">
            <a href="<%= o.p_link %>">View Expert</a>
        </div>
    </div>
</script>
