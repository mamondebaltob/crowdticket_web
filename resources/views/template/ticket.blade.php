<script type="text/template" id="template_ticket">
	<div data-ticket-id="<%= ticket.id %>" class="ticket col-md-12">
		<div class="ticket-wrapper <% if (style === 'modifyable') { %> col-md-11 <% } %>">
			<div class="ticket-body">
				<% if (type === 'funding') { %>
					<span class="text-primary">
						<span class="ticket-price"><%= ticket.price %></span>원 이상 후원
					</span>
					<% if (ticket.real_ticket_count > 0) { %>
						<span class="pull-right">
							<img src="{{ asset('/img/app/ico_ticket2.png') }}" />
							티켓
							<span class="ticket-real-count"><%= ticket.real_ticket_count %></span>매 포함
						</span>
					<% } %>
				<% } else if (type === 'sale') { %>
					<%
						var date = new Date(ticket.delivery_date);
						var yyyy = date.getFullYear();
	    				var mm = date.getMonth() + 1;
						var dd = date.getDate();
	    				var H = date.getHours();
	    				var min = date.getMinutes();
	    				if (min < 10) {
	    					min = "0" + min;
	    				}
	    				var formatted = yyyy + "년 " + mm + "월 " + dd + "일 " + H + ":" + min;  
					%>
					<span class="text-primary ticket-delivery-date"><%= formatted %></span>
					<span class="pull-right">
						<span class="ticket-price"><%= ticket.price %></span>원
					</span>
				<% } %>
				<p class="ticket-reward"><%= ticket.reward %></p>
			</div>
			<div class="ticket-footer">
				<span>
					<span class="text-primary"><%= ticket.audiences_count %></span>명이 선택 중 /
				</span>
				<% if (ticket.audiences_limit > 0) { %>
					<span>
						<span class="ticket-audiences-limit"><%= ticket.audiences_limit %></span>개 제한
					</span>
				<% } else { %>
					<span>제한 없음</span>
				<% } %>
				<% if (type === 'funding') { %>
					<%
						var date = new Date(ticket.delivery_date);
	    				var mm = date.getMonth() + 1;
						var dd = date.getDate();
	    				var formatted = mm + "월 " + dd + "일";  
					%>
					<span class="pull-right">예상 실행일 : <span class="ticket-delivery-date"><%= formatted %></span></span>
				<% } %>
			</div>
		</div>
		
		<% if (style === 'modifyable') { %>
		<div class="col-md-1">
			<p>
				<button class="btn btn-primary modify-ticket">수정</button>
				<button class="btn btn-primary delete-ticket">삭제</button>
			</p>
		</div>
		<% } %>
	</div>
</script>