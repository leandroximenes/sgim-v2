<?php
		echo"<chart>
			<license>JTAMVPF7P2O.H4X5CWK-2XOI1X0-7L</license>
	
			<chart_data>
				<row>
					<null/>
					<string>Mídia Exterior</string>
					<string>TV Paga</string>
					<string>INTERNET</string>
					<string>Guias e Listas</string>
					<string>Cinema</string>
					<string>Jornal</string>
					<string>Rádio</string>
					<string>Revista</string>
					<string>TV aberta</string>
				</row>
				<row>
					<string></string>
					<number shadow='high' bevel='data' line_color='FFFFFF' line_thickness='2' line_alpha='75' tooltip='3.12'>3.12</number>
					<number shadow='high' bevel='data' line_color='FFFFFF' line_thickness='2' line_alpha='75' tooltip='3.30'>3.30</number>
					<number shadow='high' bevel='data' line_color='FFFFFF' line_thickness='2' line_alpha='75' tooltip='4.24'>4.24</number>	
					<number shadow='high' bevel='data' line_color='FFFFFF' line_thickness='2' line_alpha='75' tooltip='1.29'>1.29</number>
					<number shadow='high' bevel='data' line_color='FFFFFF' line_thickness='2' line_alpha='75' tooltip='0.28'>0.28</number>
					<number shadow='high' bevel='data' line_color='FFFFFF' line_thickness='2' line_alpha='75' tooltip='10.20'>10.20</number>
					<number shadow='high' bevel='data' line_color='FFFFFF' line_thickness='2' line_alpha='75' tooltip='4.35'>4.35</number>
					<number shadow='high' bevel='data' line_color='FFFFFF' line_thickness='2' line_alpha='75' tooltip='6.41'>6.41</number>
					<number shadow='high' bevel='data' line_color='FFFFFF' line_thickness='2' line_alpha='75' tooltip='63.19'>63.19</number>
				</row>	
			</chart_data>
			<chart_label shadow='low' color='000000' alpha='95' size='13' position='inside' as_percentage='true' />
			<chart_pref select='true' />
			<chart_rect x='70' y='40' width='300' height='250' />
			<chart_transition type='scale' delay='1' duration='.5' order='category' />
			<chart_type>donut</chart_type>

			<filter>
				<shadow id='low' distance='2' angle='45' color='0' alpha='40' blurX='5' blurY='5' />
				<shadow id='high' distance='5' angle='45' color='0' alpha='40' blurX='10' blurY='10' />
				<shadow id='soft' distance='2' angle='45' color='0' alpha='20' blurX='5' blurY='5' />
				<bevel id='data' angle='45' blurX='5' blurY='5' distance='3' highlightAlpha='15' shadowAlpha='25' type='inner' />
				<bevel id='bg' angle='45' blurX='50' blurY='50' distance='10' highlightAlpha='35' shadowColor='0000ff' shadowAlpha='25' type='full' />
				<blur id='blur1' blurX='75' blurY='75' quality='1' />   
			</filter>
			
			<chart_pref rotation_x='0' rotation_y='20' drag='true' />
			<context_menu full_screen='true' />
			<legend transition='dissolve' x='50' y='330' width='350' bevel='low' fill_alpha='0' line_alpha='0' bullet='circle' size='12' color='000000' alpha='100' />
		
			<series_color>
				<color>329cb9</color>
				<color>e57c26</color>
				<color>829ac7</color>
				<color>b07372</color>
				<color>7a856b</color>				
				<color>376aab</color>
				<color>aa3432</color>
				<color>85a73e</color>
				<color>6d5093</color>
			</series_color>
			<series_explode>
				<number>0</number>
				<number>0</number>
				<number>25</number>
				<number>0</number>
				<number>20</number>
				<number>30</number>
				<number>0</number>
				<number>0</number>
				<number>25</number>
			</series_explode>

			<series transfer='true' />
		</chart>";
	
?>