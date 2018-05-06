﻿<?php
require_once('config.php');	
// Load Classes
C::loadClass('User');
C::loadClass('Card');
C::loadClass('CMS');
//Init User class
$User = new User();
$Card = new Card();
$Common = new Common();


function error_found(){
   C::redirect(C::link(HOST.'404.php', false, true));
}
set_error_handler('error_found');

$reqID = array();
if(isset($_GET['detail']) && trim($_GET['detail'])){
	
	$getID = explode(" ", trim($_GET['detail']));
	$getName = explode("=", trim($getID['1']));
	
	$reqID['1'] = $getID['0'];
	//$reqName = $getName['1'];
	$result = $User->query("SELECT * FROM `tblWebCards` WHERE `id` = '" . $reqID[1] . "'");
	if(isset($result) && is_array($result) && count($result) > 0){
		$_SESSION['value'] = $result;
		$reqName = $_SESSION['value'][0]['sportsName'];
	}
}


if(isset($_POST) && is_array($_POST) && count($_POST) > 0 && isset($_POST['_COMMENT_CAT']) && $_POST['_COMMENT_CAT'] == 'SPORTS_COMMENT' ){
	if(!$User->checkLoginStatus()){
		Message::addMessage("You are not logged in. Please login here to post your comment", ERR);
	}else{
		if($Card->addSportsComments($_POST, $reqID['1'])){
			Message::addMessage("Your comment will be displayed after verify by admin.", SUCCS);
    	}
    	require_once('send-commentMail.php');
	}
}
?>
<?php require_once('includes/doc_head.php'); ?>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="image-bg">
				<div class="details-page">
					<div class="image-label">
						<p class="custom-text-rotate">
							<span style="font-size:12px;">Join Code</span>
							<span style="font-size:20px;"><?php echo $_SESSION['value'][0]['joinCode']; ?></span>
						</p>
					</div>
					<div class="ask-desktop-table">
						<table class="ask-top-table">
							<tr>
								<td>
									<div class="details-page-name-sports">
										<span class="font30"><?php echo $_SESSION['value']['0']['sportsName']; ?></span><br>
										<div class="rating padding3 font15 star-margin" style="margin-bottom:-25px;">
											<!-- <div class="rateyo-readonly" data-toggle="tooltip" style="margin-left:25px;"></div> -->
											<div class="rating padding3 font15 color" style="margin-top: 0px; margin-left: 25px;margin-top:-5px;">
		                                        <?php 
	                                    		if ($_SESSION['value']['0']['rating'] == 1) {
                                    			?>
                                    			<i class="fa fa-star text-rate" aria-hidden="true"></i><i class="fa fa-star-o" aria-hidden="true"></i></i><i class="fa fa-star-o" aria-hidden="true"></i></i><i class="fa fa-star-o" aria-hidden="true"></i></i><i class="fa fa-star-o" aria-hidden="true"></i></i>
                                    			<?php
		                                    		} else if($_SESSION['value']['0']['rating'] == 2){
                                    			?>
                                    			<i class="fa fa-star text-rate" aria-hidden="true"></i><i class="fa fa-star text-rate" aria-hidden="true"></i><i class="fa fa-star-o" aria-hidden="true"></i></i><i class="fa fa-star-o" aria-hidden="true"></i></i><i class="fa fa-star-o" aria-hidden="true"></i></i>
                                    			<?php
		                                    		} else if($_SESSION['value']['0']['rating'] == 3){
                                    			?>
                                    			<i class="fa fa-star text-rate" aria-hidden="true"></i><i class="fa fa-star text-rate" aria-hidden="true"></i><i class="fa fa-star text-rate" aria-hidden="true"></i><i class="fa fa-star-o" aria-hidden="true"></i></i><i class="fa fa-star-o" aria-hidden="true"></i></i>
                                    			<?php
		                                    		} else if($_SESSION['value']['0']['rating'] == 4){
                                    			?>
                                    			<i class="fa fa-star text-rate" aria-hidden="true"></i><i class="fa fa-star text-rate" aria-hidden="true"></i><i class="fa fa-star text-rate" aria-hidden="true"></i><i class="fa fa-star text-rate" aria-hidden="true"></i><i class="fa fa-star-o" aria-hidden="true"></i></i>
                                    			<?php
		                                    		} else if($_SESSION['value']['0']['rating'] == 5){
                                    			?>
												<i class="fa fa-star text-rate" aria-hidden="true"></i><i class="fa fa-star text-rate" aria-hidden="true"></i><i class="fa fa-star text-rate" aria-hidden="true"></i><i class="fa fa-star text-rate" aria-hidden="true"></i><i class="fa fa-star text-rate" aria-hidden="true"></i>
                                    			<?php
		                                    		}
		                                    	?>
		                                    </div>
											<div class="counter-top"><?php echo $_SESSION['value']['0']['rating']; ?></div>
										</div>
										<?php $reviewCount = $User->query("SELECT COUNT(`id`) AS countReview FROM `tblSportsComment` WHERE `sportsId`='".$_SESSION['value'][0]['id']."' AND `isRecommanded`='Y'");?>
										<div class="font15" style="margin:0px 0px -25px 160px;"><?php echo $reviewCount[0]['countReview'];?> 명의 댓글 후기</div><br>
										<span class="font15"><?php echo $_SESSION['value']['0']['description']; ?></span>
									</div>
								</td>
								<td>
									<div class="details-page-joinCode">
										<span>Join Code</span><br>
										<span><?php echo $_SESSION['value']['0']['joinCode']; ?></span>
									</div>
									<div class="details-page-logo">
										<img src="<?php echo $_SESSION['value']['0']['sportsImage']; ?>" alt="Sports Logo"  style="border:4px solid #ccc;" />
									</div>
								</td>
							</tr>
						</table>
						<table class="ask-table">
							<h5 class="text-white margin-top-20">사이트 정보</h5>
							<tr>
								<th class="text-yellow font18"><?php echo $_SESSION['value']['0']['welcomeBonus']; ?></th>
								<th class="text-yellow font18"><?php echo $_SESSION['value']['0']['maxPrizeMoney']; ?>원</th>
								<th class="text-yellow font18"><?php echo $_SESSION['value']['0']['singleBet']; ?></th>
								<th class="text-yellow font18"><?php echo $_SESSION['value']['0']['crossBetting']; ?></th>
								<th class="text-yellow font18"><?php echo ($_SESSION['value']['0']['liveChat'] == 'Y') ? 'YES' : 'NO'; ?></th>
							</tr>
							<tr>
								<td class="padding-top-0">신규 첫충 보너스</td>
								<td class="padding-top-0">최대 당첨금</td>
								<td class="padding-top-0">단폴더 조건</td>
								<td class="padding-top-0">크로스 배팅</td>
								<td class="padding-top-0">Live Chat</td>
							</tr>
						</table>
					</div>
					<!-- info for mobile and tablet -->
					<div class="ask-mobile-table">
						<table class="ask-table">
							<tr>
								<td colspan="2">
									<div class="details-page-name">
										<span class="text-capitalize"><?php echo $_SESSION['value']['0']['sportsName']; ?></span><br>
										<span><?php echo $_SESSION['value']['0']['description']; ?></span><br>
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<div class="details-page-joinCode">
										<span>Join Code</span><br>
										<span><?php echo $_SESSION['value']['0']['joinCode']; ?></span>
									</div>
									<div class="details-page-logo">
										<img src="<?php echo $_SESSION['value']['0']['sportsImage']; ?>" alt=""  style="border:4px solid #ccc;" />
									</div>
								</td>
							</tr>
						</table>
						<table class="ask-table">
							<tr>
								<td class="text-yellow">신규 첫충 보너스</td>
								<td><?php echo $_SESSION['value']['0']['welcomeBonus']; ?></td>
							</tr>
							<tr>
								<td class="text-yellow">최대 당첨금</td>
								<td><?php echo $_SESSION['value']['0']['maxPrizeMoney']; ?>원</td>
							</tr>
							<tr>
								<td class="text-yellow">단폴더 조건</td>
								<td><?php echo $_SESSION['value']['0']['singleBet']; ?></td>
							</tr>
							<tr>
								<td class="text-yellow">크로스 배팅</td>
								<td><?php echo $_SESSION['value']['0']['crossBetting']; ?></td>
							</tr>
							<tr>
								<td class="text-yellow">미니게임</td>
								<td><?php echo $_SESSION['value']['0']['miniGame']; ?></td>
							</tr>
						</table>
					</div>
					<!-- info for mobile and tablet end -->
				</div>
				<div class="col-sm-12 content-button">
					<div class="col-sm-4">
						<a href="http://<?php echo $_SESSION['value']['0']['link']; ?>" class="btn btn-ask-red btn-w100 text-capitalize padding10 font15"><b><i class="fa fa-reply-all margin-right-5" aria-hidden="true"></i> 사이트 바로가기</b></a>
					</div>
					<div class="col-sm-4">
						<a href="<?php echo $_SERVER['REQUEST_URI'];?>#writeReview" class="btn btn-ask-green btn-w100 text-capitalize padding10 font15"><b><i class="fa fa-pencil margin-right-5" aria-hidden="true"></i> 후기 등록하기</b></a>
					</div>
					<div class="col-sm-4">
						<a href="submitComplaint.php" class="btn btn-ask-grd-blue btn-w100 text-capitalize padding10 font15"><b><i class="fa fa-gavel margin-right-5" aria-hidden="true"></i> 분쟁 해결 신청하기</b></a>
					</div>
				</div>
				<div class="content row fixed-top">
					<div class="col-sm-8 margin-top-10">
						<span class="font15 text-white text-uppercase"><b><?php echo $_SESSION['value']['0']['sportsName']; ?></b></span>
						<span class="font15 text-white text-uppercase"><b> &nbsp;&nbsp;/&nbsp;&nbsp; 가입코드 : <?php echo $_SESSION['value']['0']['joinCode']; ?></b></span>
					</div>
					<div class="col-sm-4">
						<a href="http://<?php echo $_SESSION['value']['0']['link']; ?>" class="btn btn-ask-red btn-w100 text-capitalize padding10 font15"><b><i class="fa fa-reply-all margin-right-5" aria-hidden="true"></i> 사이트 바로가기</b></a>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="ask-content" id="ask-content">
				<div class="row">
					<div class="col-lg-9 col-md-9" id="show-pop-up">
						<div class="ask-page-content">
							<div class="ask-page-content-header">
								<h3 class="heading text-white text-uppercase">사이트 리뷰 </h3><!--  border-bottom-5 -->
							</div>
							<div class="ask-page-content-body-details">
								<div class="content" id="content-read-more">
									<div class="text-white"><?php echo $_SESSION['value']['0']['sportsReview']; ?></div>
								</div>
							</div>
						</div>
						<div class="ask-page-content ask-land-page-content">
							<div class="ask-page-content-header">
								<h3 class="text-uppercase">배팅 보너스 </h3><!--  border-bottom-5 -->
								<p class="custom-p custom-text">Here's a list of bonus codes related to this sports </p>
							</div>
							<div class="ask-page-content-body ask-detail-page-card">
							<?php
								$res = $User->query("SELECT * FROM `tblBonusCards` WHERE `sportsName` = '$reqName' LIMIT 3");
									if(isset($res) && is_array($res) && count($res) > 0){
										foreach ($res as $id => $data) {
							?>
								<div class="col-md-3 col-sm-3 col-xs-3 padding0 ask-land-web-card">
									<div class="ask-cards">
										<div class="ask-item-bonus-card">
											<div class="front">
												<div class="cardHeader">
				                                    <a href="bonus-details/<?php echo $data['id']?>/<?php echo $data['bonusName']?>"><h5><?php echo $data['bonustype']; ?></h5></a>
													<span class="fa fa-info info" style="font-size:14px;"></span>
				                                </div>
				                                <div class="cardLogo" style="overflow:hidden;">
				                                    <a href="bonus-details/<?php echo $data['id']?>/<?php echo $data['bonusName']?>"><img src="<?php echo $data['bonusImage'];?>" class="img-responsive" style="height:87px;"  alt=""></a>
				                                    <div class="cardReview text-center text-black">
				                                    	<span class="bonus-name text-center text-uppercase <?php if(strlen($data['bonusName']) > 10){ echo "font12";}?>"><?php echo $data['bonusName'];?></span>
					                                    <div class="rating padding3 font13 color" style="margin-top: 0px; margin-left: 2px;">
					                                        <?php 
				                                    		if ($data['rating'] == 1) {
			                                    			?>
			                                    			<i class="fa fa-star first" aria-hidden="true"></i><i class="fa fa-star-o" aria-hidden="true"></i></i><i class="fa fa-star-o" aria-hidden="true"></i></i><i class="fa fa-star-o" aria-hidden="true"></i></i><i class="fa fa-star-o" aria-hidden="true"></i></i>
			                                    			<?php
					                                    		} else if($data['rating'] == 2){
			                                    			?>
			                                    			<i class="fa fa-star second" aria-hidden="true"></i><i class="fa fa-star second" aria-hidden="true"></i><i class="fa fa-star-o" aria-hidden="true"></i></i><i class="fa fa-star-o" aria-hidden="true"></i></i><i class="fa fa-star-o" aria-hidden="true"></i></i>
			                                    			<?php
					                                    		} else if($data['rating'] == 3){
			                                    			?>
			                                    			<i class="fa fa-star third" aria-hidden="true"></i><i class="fa fa-star third" aria-hidden="true"></i><i class="fa fa-star third" aria-hidden="true"></i><i class="fa fa-star-o" aria-hidden="true"></i></i><i class="fa fa-star-o" aria-hidden="true"></i></i>
			                                    			<?php
					                                    		} else if($data['rating'] == 4){
			                                    			?>
			                                    			<i class="fa fa-star four" aria-hidden="true"></i><i class="fa fa-star four" aria-hidden="true"></i><i class="fa fa-star four" aria-hidden="true"></i><i class="fa fa-star four" aria-hidden="true"></i><i class="fa fa-star-o" aria-hidden="true"></i></i>
			                                    			<?php
					                                    		} else if($data['rating'] == 5){
			                                    			?>
															<i class="fa fa-star five" aria-hidden="true"></i><i class="fa fa-star five" aria-hidden="true"></i><i class="fa fa-star five" aria-hidden="true"></i><i class="fa fa-star five" aria-hidden="true"></i><i class="fa fa-star five" aria-hidden="true"></i>
			                                    			<?php
					                                    		}
					                                    	?>
					                                    </div>
					                                    <div class="ask-code">
				                                        	<p class="custom-border1">가입코드</p> <br>
				                                        	<span class="custom-border"><?php echo $data['joinCode']; ?></span>
					                                    </div>
					                                </div>
				                                </div>
				                                <div class="mainView" style="overflow:hidden;">
			                                        <div class="bonus">
			                                            <div class="bonusAmount">
			                                                <span class="text-center"><?php echo $data['bonusAmount']; ?></span>
			                                            </div>
			                                            <div class="bonusType">
			                                                <span class="text-center"><?php echo $data['bonusName'];?></span>
			                                            </div>
			                                        </div>
			                                    </div>
			                                    <div class="bonusCode text-center">
			                                        <span style="font-size:12px">배팅 보너스</span><br>
			                                        <span><b><?php echo $data['bonusCode']; ?></b></span>
			                                    </div>
				                                <div class="playNow custom-play-now">
													<a href="<?php echo $data['link'];?>" class="btn btn-ask btn-w100"><b>GET NOW</b></a>
												</div>
											</div><!-- front -->
											<div class="back">
												<div class="cardHeader">
				                                    <a href="bonus-details/<?php echo $data['id']?>/<?php echo $data['bonusName']?>"><h5 class="text-uppercase"><?php echo $data['bonustype']; ?></h5></a>
				                                    <span class="pull-right fa fa-close info"></span>
				                                </div>
				                                <div class="bonus-desc">
				                                	<ul class="information-list">
														<li>
															<div class="list-left">Bonus</div>
															<div class="list-right"><?php echo $data['bonusAmount']; ?></div>
														</li>
														<li>
															<div class="list-left">Sports</div>
															<div class="list-right"><?php echo $data['sportsName']; ?></div>
														</li>
														<li>
															<div class="list-left">W.R</div>
															<div class="list-right"><?php echo $data['wageringRequirements']; ?></div>
														</li>
														<li>
															<div class="list-left">Type</div>
															<div class="list-right"><?php echo $data['bonustype']; ?></div>
														</li>
													</ul>
				                                </div>
				                                <div class="text-center">
													<a href="bonus-details/<?php echo $data['id']?>/<?php echo $data['bonusName']?>" class="readMore">Read More</a>
												</div>
				                                <div class="getNow">
				                                    <a href="<?php echo $data['link'];?>" class="btn btn-ask btn-w100">GET NOW</a>
				                                </div>
											</div><!-- back -->
									    </div><!-- ask-item-bonus-card -->
									</div>
								</div><!-- col-md-3 -->
								<?php
									}
								} else {
								?>
								<p class="text-yellow">THIS SPORTS HAVE ONLY ONE BONUS AVAILABLE</p>
								<?php
									}
								?>
							</div>
						</div><!-- bonus code landing-->
						<div class="ask-page-content ask-land-page-content">
							<div class="ask-page-content-header">
								<h3 class="text-uppercase">사이트 분쟁 <span class="pull-right" style="font-size:12px;line-height:30px;"><a href="complaint.php" class="text-white">전체보기</a></span></h3><!--  border-bottom-5 -->
								<p class="custom-p custom-text">Have troubles with Sports? <a href="submit-complaint/">Submit a complaint</a> or <a href="">Learn more</a> .  </p>
							</div>
							<div class="ask-page-content-body ask-detail-page-card">
							<?php
								//$siteName = $_SESSION['value']['0']['link'] .'/'. $_SESSION['value']['0']['siteName'];
								$result = $User->query("SELECT `id`, `reason`, `complaintTitle`, `complaintText`, `amount`, `isVerified`, `status` FROM `tblComplaints` WHERE `isVerified` = 'Y' AND `siteName` = '" . $_SESSION['value']['0']['siteName'] . "'  ORDER BY `updatedOn` desc LIMIT 3");
								if(is_array($result) && count($result) > 0){
									foreach ($result as $key => $value) {				
								?>
								<div class="col-md-3 col-sm-3 col-xs-3 padding0  ask-land-web-card">
									<div class="ask-cards">
										<div class="ask-item-complain-card">
											<div class="front">
												<a href="complaint-details/<?php echo $value['id'];?>/">
													<div class="complain-logo">
														<?php
														if($value['status'] == 'P'){
													?>
														<div class="ask-ripple ask-ripple-pending">
															<span class="glyphicon glyphicon-hourglass ask-complai-logo complai-pending"></span>
															<span class="ripple-pending"></span>
														</div>
													<?php
														}else if($value['status'] == 'S'){
													?>
														<div class="ask-ripple ask-ripple-success">
															<span class="glyphicon glyphicon-ok-sign ask-complai-logo complai-success"></span>
															<span class="ripple-success"></span>
														</div>
													<?php
														}else if($value['status'] == 'U'){
													?>
														<div class="ask-ripple ask-ripple-reject">
															<span class="glyphicon glyphicon-remove-circle ask-complai-logo complai-reject"></span>
															<span class="ripple-reject"></span>
														</div>
													<?php } ?>
													</div>
												</a>
														<span class="pull-right fa fa-info info"></span>
												<?php
														if($value['status'] == 'P'){
													?>
														<p class="text-center text-capitalize text-pending pt5"><b>Pending</b></p>
													<?php
														}else if($value['status'] == 'S'){
													?>
														<p class="text-center text-capitalize text-sucess pt5"><b>solved</b></p>
													<?php
														}else if($value['status'] == 'U'){
													?>
														<p class="text-center text-capitalize text-reject pt5"><b>unsolved</b></p>
													<?php } ?>
												<div class="complain-short-desc" style="padding-top: 0px;">
													<p><?php echo $value['complaintTitle']; ?></p>
												</div>
												<div class="complain-Date" style="padding-top: 2px;">
													<p> <span style="font-size:24px;font-weight:900;"><?php echo $value['amount']; ?> 만원</span><br>
														<?php echo $value['reason']; ?> </p>
												</div>
											</div><!-- front -->
											<div class="back">
												<div class="complain-short-desc">
													<p><?php echo $value['complaintTitle']; ?></p>
													<span class="pull-right fa fa-close info"></span>
												</div>
												<div class="complain-about">
													<p><?php echo $value['complaintText']; ?></p>
													<div class="text-center">
														<a href="complaint-details/<?php echo $value['id'];?>/" class="readMore">Read More</a>
													</div>
												</div>
											</div><!-- back -->
										</div><!-- ask-item-complain-card -->
									</div>
								</div><!-- col-md-3 -->
								<?php
								 }
							}else{
								 	echo '<p class="text-yellow">THIS SPORT HAS NO COMPLAINT</p>';
								 }
							?>
							</div>
						</div><!-- bonus code landing-->
						<div class="ask-page-content">
							<div class="ask-page-content-header">
								<h3 class="heading text-white text-uppercase">사이트 세부사항 </h3><!--  border-bottom-5 -->
							</div>
							<div class="ask-page-content-body-details"> 
								<div class="row content">

									<table class="ask-table text-bold custom-table-padding">
									<?php
									$result = $User->query("SELECT * FROM `tblWebCards` WHERE `id` = $reqID[1]");
										if(isset($result) && is_array($result) && count($result) > 0){
											$value = $result;
									?>
										<tr>
											<td> Join Code :</td>
											<td><a href="<?php echo $_SERVER['REQUEST_URI'];?>" class="btn btn-ask-white"><?php echo $value['0']['joinCode']; ?></a></td>
										</tr>
										<tr>
											<td style="width:30%;"> Sports Name :</td>
											<td><a href="<?php echo $_SERVER['REQUEST_URI'];?>"><?php echo $value['0']['sportsName']; ?></a></td>
										</tr>
										<tr>
											<td> Official Website :</td>
											<td><a href="sports/?link[]=<?php echo $value['0']['link']; ?>"><?php echo $value['0']['link']; ?></a></td>
										</tr>
										<tr>
											<td>Welcome Bonus :</td>
											<td><a href="sports/?welcomeBonus[]=<?php echo $value['0']['welcomeBonus']; ?>"><?php echo $value['0']['welcomeBonus']; ?></a></td>
										</tr>
										<tr>
											<td>Mini Games :</td>
											<td><a href="javascript:void(0)"><?php echo $value['0']['miniGame']; ?></a></td>
										</tr>
										<tr>
											<td>Cross Bettingaaaaaaaa :</td>
											<td><a href="sports/?crossBetting[]=<?php echo $value['0']['crossBetting']; ?>"><?php echo $value['0']['crossBetting']; ?></a></td>
										</tr>
										<?php if($value['0']['dwMethods'] !=''){?>
										<tr>
											<td>D/W Methods :</td>
											<td><a href="sports/?dwMethods[]=<?php echo $value['0']['dwMethods']; ?>"><?php echo $value['0']['dwMethods']; ?></a></td>
										</tr>
										<?php }?>
										<?php if($value['0']['maxBettingAmount'] !='' && $value['0']['maxBettingAmount'] != 0){?>
										<tr>
											<td>Max Betting Amount :</td>
											<td><a href="sports/?maxBettingAmount=<?php echo $value['0']['maxBettingAmount']; ?>"><?php echo $value['0']['maxBettingAmount']; ?>원</a></td>
										</tr>
										<?php }?>
										<?php if($value['0']['minBettingAmount'] !='' && $value['0']['minBettingAmount'] != 0){?>
										<tr>
											<td>Min Betting Amount :</td>
											<td><a href="sports/?minBettingAmount=<?php echo $value['0']['minBettingAmount']; ?>"><?php echo $value['0']['minBettingAmount']; ?>원</a></td>
										</tr>
										<?php }?>
										<?php if($value['0']['firstDepositeBonus'] !=''){?>
										<tr>
											<td>First Deposite Bonus :</td>
											<td><a href="sports/?firstDepositeBonus[]=<?php echo $value['0']['firstDepositeBonus']; ?>"><?php echo $value['0']['firstDepositeBonus']; ?></a></td>
										</tr>
										<?php }?>
										<?php if($value['0']['maxWithdrawlLimit'] !=''){?>
										<tr>
											<td>Max Withdrawl Limit :</td>
											<td><a href="sports/?maxWithdrawlLimit[]=<?php echo $value['0']['maxWithdrawlLimit']; ?>"><?php echo $value['0']['maxWithdrawlLimit']; ?></a></td>
										</tr>
										<?php }?>
										<?php if($value['0']['dailyBonus'] !=''){?>
										<tr>
											<td>Daily Bonus :</td>
											<td><a href="sports/?dailyBonus[]=<?php echo $value['0']['dailyBonus']; ?>"><?php echo $value['0']['dailyBonus']; ?></a></td>
										</tr>
										<?php }?>
										<?php if($value['0']['rebateBonus'] !=''){?>
										<tr>
											<td>Rebate Bonus :</td>
											<td><a href="sports/?rebateBonus[]=<?php echo $value['0']['rebateBonus']; ?>"><?php echo $value['0']['rebateBonus']; ?></a></td>
										</tr>
										<?php }?>
										<?php if($value['0']['rollingBonus'] !=''){?>
										<tr>
											<td>Rolling Bonus :</td>
											<td><a href="sports/?rollingBonus[]=<?php echo $value['0']['rollingBonus']; ?>"><?php echo $value['0']['rollingBonus']; ?></a></td>
										</tr>
										<?php }?>
										<?php
										$res = $value[0]['sportsOtherDetails'];
										if(isset($res)){
											$res = explode('+', $value[0]['sportsOtherDetails']);
											$label = json_decode($res['0']);
											$datas = json_decode($res['1']);
											
											foreach ($label as $index => $val) {
										?>
										<tr>
											<td> <?php echo $val; ?> :</td>
											<td><a href=""> <?php echo $datas[$index]; ?> </a></td>
										</tr>
										<?php
													}
												}
											//}
										}
										?>
										<?php if($value['0']['established'] !=''){?>
										<tr>
											<td>Established :</td>
											<td><a href="sports/?established[]=<?php echo $value['0']['established']; ?>"><?php echo $value['0']['established']; ?></a></td>
										</tr>
										<?php }?>
										<?php if($value['0']['liveChat'] !=''){?>
										<tr>
											<td>Live Chat :</td>
											<td><a href="sports/?liveChat[]=<?php echo $value['0']['liveChat']; ?>"><?php echo $value['0']['liveChat']; ?></a></td>
										</tr>
										<?php }?>
									</table>
								</div>
							</div>
						</div>
						<div class="ask-page-content">
							<div class="ask-page-content-header">
								<h3 class="heading text-white text-uppercase">사이트 후기 </h3><!--  border-bottom-5 -->
							</div>
							<div class="ask-page-content-body-details">
								<div class="col-lg-12 col-md-12 commentsContainer">
								<?php
								$result = $User->query("SELECT `TSC`.`id`, `TSC`.`gdComments`, `TSC`.`badComments`, `TSC`.`rating`, `TSC`.`updatedOn`, `TU`.`userId` FROM `tblSportsComment` as `TSC`, `tblUser` as `TU`  WHERE `TSC`.`isRecommanded` = 'Y' AND `TSC`.`userId` = `TU`.`id` AND `TSC`.`sportsId` = '" . $reqID[1] . "'");
								if(is_array($result) && count($result) > 0){
									$index = 0;
								?>
									<div class="margin-top-20 commentsFilterArea">
										<a href="" class="text-yellow m-r-10 commentsFilter" data-filter="ALL">전체보기</a>
										<a href="" class="text-yellow m-r-10 commentsFilter" data-filter="GOOD">평점 높은 댓글순</a>
										<a href="" class="text-yellow m-r-10 commentsFilter" data-filter="BAD">평점 낮은 댓글순</a>
									</div>
								<?php
									foreach ($result as $key => $value) {
										$response_id = $value['id'];
								?>
									<table class="ask-table commentFilterTbl" data-rate="<?php echo $value['rating'];?>" data-idx="<?php echo $index;?>">
										<tr>
											<td style="width:15%;" class="userIconsDisplay">
												<div class="content img-circle user-comment text-center">
													<?php $firstLt = $value['userId']; ?>
													<p class="text-uppercase" style="padding-top:10px;"><b><?php echo $firstLt[0];?></b></p>
												</div>
											</td>
											<td>
												<div class="content arrow-content">
													<h5 class="page-header comment-preview-header margin-top-0">
														<span class="text-yellow margin-right-5"><?php echo $value['userId']; ?></span>
														<?php $updateDate = explode(' ', $value['updatedOn']);?>
														<span class="text-white">(Reviewed on <?php echo $updateDate[0]; ?>)</span>
														<span class="rating padding3 font13 pull-right cmntRate">
					                                        <?php 
				                                    		if ($value['rating'] == 1) {
			                                    			?>
			                                    			<i class="fa fa-star text-white" aria-hidden="true"></i><i class="fa fa-star-o" aria-hidden="true"></i></i><i class="fa fa-star-o" aria-hidden="true"></i></i><i class="fa fa-star-o" aria-hidden="true"></i></i><i class="fa fa-star-o" aria-hidden="true"></i></i>
			                                    			<?php
					                                    		} else if($value['rating'] == 2){
			                                    			?>
			                                    			<i class="fa fa-star text-white" aria-hidden="true"></i><i class="fa fa-star text-white" aria-hidden="true"></i><i class="fa fa-star-o" aria-hidden="true"></i></i><i class="fa fa-star-o" aria-hidden="true"></i></i><i class="fa fa-star-o" aria-hidden="true"></i></i>
			                                    			<?php
					                                    		} else if($value['rating'] == 3){
			                                    			?>
			                                    			<i class="fa fa-star text-white" aria-hidden="true"></i><i class="fa fa-star text-white" aria-hidden="true"></i><i class="fa fa-star text-white" aria-hidden="true"></i><i class="fa fa-star-o" aria-hidden="true"></i></i><i class="fa fa-star-o" aria-hidden="true"></i></i>
			                                    			<?php
					                                    		} else if($value['rating'] == 4){
			                                    			?>
			                                    			<i class="fa fa-star text-white" aria-hidden="true"></i><i class="fa fa-star text-white" aria-hidden="true"></i><i class="fa fa-star text-white" aria-hidden="true"></i><i class="fa fa-star text-white" aria-hidden="true"></i><i class="fa fa-star-o" aria-hidden="true"></i></i>
			                                    			<?php
					                                    		} else if($value['rating'] == 5){
			                                    			?>
															<i class="fa fa-star text-white" aria-hidden="true"></i><i class="fa fa-star text-white" aria-hidden="true"></i><i class="fa fa-star text-white" aria-hidden="true"></i><i class="fa fa-star text-white" aria-hidden="true"></i><i class="fa fa-star text-white" aria-hidden="true"></i>
			                                    			<?php
					                                    		}
					                                    	?>
					                                    </span>
													</h5>
													<div class="comment-show">
														<table class="ask-table">
															<tr>
																<td style="width:10%;padding-left:20px;padding-bottom:10px;">
																	<i class="fa fa-thumbs-up text-green" aria-hidden="true"></i>
																</td>
																<td style="padding-bottom:10px;">
																	<span><?php echo $value['gdComments']; ?></span>
																</td>
															</tr>
															<tr>
																<td style="width:10%;padding-left:20px;padding-bottom:10px;">
																	<i class="fa fa-thumbs-down text-red" aria-hidden="true"></i>
																</td>
																<td style="padding-bottom:10px;">
																	<span><?php echo $value['badComments']; ?></span>
																</td>
															</tr>
														</table>
													</div>
												</div>			
											</td>
										</tr>
										<?php
										$logedInID = (int)User::loggedInUserId() > 0 ? User::loggedInUserId() : 0;
										if($User->checkLoginStatus()){
											$userid = $User->query("SELECT `userId`, `groupId`, `siteName` FROM `tblUser` WHERE `id` = '" . $logedInID . "' LIMIT 0,1");

											if($userid[0]['userId'] == $value['userId']){
												
												$User->query("UPDATE `tblCommentResponse` SET `checkUser` = 'Y' WHERE `responseId`='". $response_id ."' AND `categoryId` = '" . $reqID[1] . "' AND `isVerified`='Y' AND `category`='1'");
											}
										}

											$innrRes = $User->query("SELECT `id`, `userId`, `responseId`, `comment` FROM `tblCommentResponse` WHERE `categoryId` = '" . $reqID[1] . "' AND `responseId`= '" . $response_id . "' AND `isVerified`='Y' AND `category`='1' ORDER BY `createdOn`");
													if(is_array($innrRes) && count($innrRes) > 0){
														foreach ($innrRes as $key1 => $value1) {
										?>
										<tr>
						                    <td style="width:15%;" class="userIconsDisplay">&nbsp;</td>
						                    <td>
						                        <div class="content" style="margin-top: -22px;">
													<?php
													$res1 = $User->query("SELECT `id`, `userId`, `groupId`, `siteName` FROM `tblUser` WHERE `id` = '" . $value1['userId'] . "'");
													if(is_array($res1) && count($res1) > 0){
														foreach ($res1 as $index1 => $val1) {
															$gID = $val1['groupId'];
															$admin = 'Admin';
													?>
						                            <h5 class="page-header comment-preview-header margin-top-0">
						                                <span class="text-yellow margin-right-5"><?php echo ($gID == 0 ? $admin : $val1['siteName']); ?></span>
						                            </h5>
						                            <?php
																}
															}
													?>
						                            <div class="comment-show">
						                                <table class="ask-table">
						                                    <tr>
						                                        <td style="padding-bottom:10px;">
						                                            <span><?php echo $value1['comment']; ?></span>
						                                        </td>
						                                    </tr>
						                                </table>
						                            </div>
						                        </div>      
						                  </td>
						                </tr>
						                <?php
													}
												}
										?>
									</table>
								<?php
										$index++;
									}
								}else{
								?>
								<p class="text-yellow text-uppercase" style="padding-top:20px;">BE the first one to comment here....</p>
								<?php
								}
								?>
									
								</div>
							</div>
						</div>
						<div class="ask-page-content" id="writeReview">
							<div class="ask-page-content-header">
								<h3 class="heading text-white text-uppercase">댓글 등록 </h3><!--  border-bottom-5 -->
							</div>
							<div class="ask-page-content-body-details">
								<form action="" method="post" enctype="multipart/form-data">
									<div class="col-lg-12 col-md-12">
										<table class="ask-table" style="margin-bottom:-35px;">
											<tr>
												<td style="width:15%;">
													<div class="content img-circle user-comment text-center">
														<i class="fa fa-thumbs-up margin-top-15 text-green" aria-hidden="true"></i>
													</div>
												</td>
												<td>
													<div class="content arrow-content">
														<input type="hidden" value="YES" name="needLogin" />
														<textarea name="likeComment" id="" cols="" rows="3" placeholder="어떠한 점이 좋으셨나요?"></textarea>
														<input type="hidden" name="_COMMENT_CAT" value="SPORTS_COMMENT" />
													</div>			
												</td>
											</tr>
										</table>
										<table class="ask-table">
											<tr>
												<td style="width:15%;">
													<div class="content img-circle user-comment text-center">
														<i class="fa fa-thumbs-down margin-top-15 text-red" aria-hidden="true"></i>
													</div>
												</td>
												<td>
													<div class="content arrow-content">
														<textarea name="dislikeComment" id="" cols="" rows="3" placeholder="어떠한 점이 불편하셨나요?"></textarea>
													</div>			
												</td>
											</tr>
										</table>
										<div class="col-md-10 col-md-offset-2">
											<p class="text-white">해당 사이트를 평가해주세요.</p>
											<div class="rating font13 text-white star-margin" style="margin-top:-7px;">
		                                        <div class="rateyo-readonly-widg" data-toggle="tooltip" title=""></div>
		                                        <div class="counter ratingCounter">5</div>
		                                        <input type="hidden" id="commentRate" name="commentRate" value="5" />
		                                        <input type="hidden" name="category" value="SPORTS" />
		                                    </div>
		                                    <p class="text-white"><input type="checkbox" name="checkPost" /><span style="margin-left:15px;">저의 후기는 본인 자신의 경험을 토대로 작성하였으며 진실된 의견임을 선언합니다. 저는 해당 사이트 직원이 아니며, 해당 리뷰로 인해 사이트로부터 인센티브 혹은 어떠한 보너스도 받지 않았습니다. 배팅타임에서는 거짓된 리뷰에 대해 엄격한 조치를 취할 것입니다. </span></p>
		                                    <div style="margin-top:20px;margin-bottom:10px;">
		                                    	<button type="submit" class="btn btn-ask-red" style="margin:0px 20px 0px 0px;">작성하기</button><a href="posting-guidlines.php" class="text-yellow">댓글 가이드 라인 확인하기</a>
	                                    	</div>
										</div>
									</div>
								</form>
							</div>
						</div>
						<div class="ask-page-content ask-land-page-content">
							<div class="ask-page-content-header">
								<h3 class="text-uppercase">최신 소식</h3><!--  border-bottom-5 -->
								<p class="custom-p custom-text">Here's a list of casino bonuses and promotions that is updated daily with the latest coupon code,no deposite bonuses, free spin, cash back welcome offers, match deposite bonuses, high roller bonuses and many more.. </p>
							</div>
							<div class="ask-page-content-body ask-detail-page-card"><!--  -->
								<?php
								$result = $User->query("SELECT `id`, `title`, `newsDesc`, `newsImage`, `updatedOn` FROM `tblNewsBlog` WHERE `id` != '" . $_SESSION['value']['0']['id'] . "' AND `isNews` = 'N' ORDER BY `updatedOn` desc LIMIT 3");
								if(is_array($result) && count($result) > 0){
									foreach ($result as $key => $value) {				
							?>
								<div class="col-md-3 col-sm-3 col-xs-3 padding0 ask-land-web-card">
									<div class="ask-cards">
										<div class="ask-item-news-card">
											<div class="front">
												<span class="pull-right fa fa-info info" style="top: 254px; padding: 6px 10px 19px;"></span>
												<div class="news-logo">
													<a href="news-details/<?php echo $value['id']; ?>/<?php echo str_replace(' ', '-', $value['title']); ?>/">
														<img src="<?php echo $value['newsImage']; ?>" class="img-responsive" alt="" />
													</a>
												</div>
												<div class="news-short-desc">
													<a href="news-details/<?php echo $value['id']; ?>/<?php echo str_replace(' ', '-', $value['title']); ?>/"><p class="text-black"><?php echo $value['title']; ?></p></a>
												</div>
												<div class="news-Date">
													<?php 
													$date = explode(' ', $value['updatedOn']);
													$date = $date[0];
													$date = date_create($date);
													 $postDate = date_format($date, 'F d , Y')
													?>
													<p> <?php echo $postDate;?></p>
												</div>
											</div><!-- front -->
											<div class="back">
												<div class="news-short-desc">
													<a href="news-details/<?php echo $value['id']; ?>/<?php echo str_replace(' ', '-', $value['title']); ?>/"><p class="text-black"><b><?php echo $value['title']; ?></b></p></a>
													<!--<span class="pull-right fa fa-close info"></span>-->
												</div>
												<div class="news-about">
													<p class="text-justify"><?php echo C::contentMorewithoutlink($value['newsDesc'], 150); ?></p>
												</div>
												<div class="news-reamore">
													<div class="text-center">
														<a href="news-details/<?php echo $value['id'].'/'.str_replace(' ', '-', $value['title']).'/';?>" class="readMore">Read More</a>
													</div>
												</div>
												<span class="pull-right fa fa-close info" style="top: 250px; padding: 4px 6px 19px;"></span>
											</div><!-- back -->
										</div><!-- ask-item-news-card -->
									</div>
								</div><!-- col-md-3 -->
								<?php
								}
							}
							?>
							</div>
						</div><!-- verified sports landing -->
					</div><!-- col-lg-9 col-md-9 -->
					<div class="col-lg-3 col-md-3 sticky_column" style="padding-left: 0px;">
						<?php require_once('includes/sportsRecommend.php'); ?>
					</div>
				</div><!-- row -->
			</div><!-- ask-content -->
		</div><!-- parent-container -->
<?php require_once('includes/doc_footer.php'); ?>