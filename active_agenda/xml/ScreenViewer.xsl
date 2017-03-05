<?xml version="1.0"?>
<!-- 
LICENSE NOTE:

Copyright  2003-2006 Active Agenda Inc., All Rights Reserved.

Unless explicitly acquired and licensed from Licensor under a "commercial license",
the contents of this file are subject to the Reciprocal Public License ("RPL")
Version 1.4, or subsequent versions as allowed by the RPL,and You may not copy
or use this file in either source code or executable form, except in compliance
with the terms and conditions of the RPL. You may obtain a copy of the RPL from
Active Agenda Inc. at http://www.activeagenda.net/license.

All software distributed under the Licenses is provided strictly on an "AS IS"
basis, WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESS OR IMPLIED, AND ACTIVE AGENDA
INC. HEREBY DISCLAIMS ALL SUCH WARRANTIES, INCLUDING WITHOUT LIMITATION, ANY 
WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, QUIET ENJOYMENT,
OR NON-INFRINGEMENT. See the Licenses for specific language governing rights and
limitations under the Licenses.

author         Dan Zahlis <dzahlis@activeagenda.net>
author         Mattias Thorslund <mthorslund@activeagenda.net>
copyright      2003-2006 Active Agenda Inc.
license        http://www.activeagenda.net/license
-->
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	<xsl:template match="ViewScreen">
		<table width="80%" align="center" bgcolor="#0193a6">
			<tr>
				<td colspan="2">
					<H2>
						<xsl:choose>
							<xsl:when test="contains(@phrase, '|')">
								<xsl:value-of select="substring-before(@phrase, '|')"/>
							</xsl:when>
							<xsl:otherwise>
								<xsl:value-of select="@phrase"/>
							</xsl:otherwise>
						</xsl:choose>
					</H2>
				</td>
			</tr>
			<tr>
				<td>
					<table cellpadding="2">
						<xsl:apply-templates select="ViewField"/>
					</table>
				</td>
			</tr>
			<xsl:apply-templates select="ViewGrid"/>
		</table>
		<br/>
		<br/>
	</xsl:template>
	<xsl:template match="EditScreen">
		<table width="80%" align="center" bgcolor="#0193a6">
			<tr>
				<td colspan="2">
					<H2>
						<xsl:choose>
							<xsl:when test="contains(@phrase, '|')">
								<xsl:value-of select="substring-before(@phrase, '|')"/>
							</xsl:when>
							<xsl:otherwise>
								<xsl:value-of select="@phrase"/>
							</xsl:otherwise>
						</xsl:choose>
					</H2>
				</td>
			</tr>
			<tr>
				<td>
					<table cellpadding="2">
						<xsl:apply-templates select="ViewField|EditField|MoneyField|MemoField|ComboField|PersonComboField|CodeComboField|RadioField|CodeRadioField|CheckBoxField|EditPlusCodeField"/>
						<tr>
							<td colspan="2" align="right" class="fld">
								<input type="button" name="Save" value="Save"/>
								<input type="button" name="Delete" value="Delete"/>
								<input type="button" name="Cancel" value="Cancel"/>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<xsl:apply-templates select="ViewGrid|EditGrid|CheckGrid|CodeCheckGrid"/>
		</table>
		<br/>
		<br/>
	</xsl:template>
	<xsl:template match="SearchScreen">
		<table width="80%" align="center" bgcolor="#0193a6">
			<tr>
				<td colspan="2">
					<H2>
						<xsl:choose>
							<xsl:when test="contains(@phrase, '|')">
								<xsl:value-of select="substring-before(@phrase, '|')"/>
							</xsl:when>
							<xsl:otherwise>
								<xsl:value-of select="@phrase"/>
							</xsl:otherwise>
						</xsl:choose>
					</H2>
				</td>
			</tr>
			<tr>
				<td>
					<table cellpadding="2">
						<xsl:apply-templates select="EditField|MoneyField|MemoField|ComboField|PersonComboField|CodeComboField|RadioField|CodeRadioField|CheckBoxField|EditPlusCodeField"/>
						<tr>
							<td colspan="2" align="right" class="fld">
								<input type="button" name="Search" value="Search"/>
								<input type="button" name="Stats" value="Stats"/>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<br/>
		<br/>
	</xsl:template>
	<xsl:template match="ViewField">
		<xsl:variable name="nametolookup" select="@name"/>
		<xsl:variable name="nd" select="/Module/ModuleFields/*[@name=$nametolookup]"/>
		<xsl:variable name="linkfield" select="@link"/>
		<xsl:variable name="lnd" select="/Module/ModuleFields/*[@name=$linkfield]"/>
		<tr>
			<td class="lbl" width="30%" title="{substring-after($nd/@phrase, '|')}"><xsl:value-of select="substring-before($nd/@phrase, '|')"/>:</td>
			<td class="fld" width="70%" title="{substring-after($nd/@phrase, '|')}">
				<xsl:choose>
					<xsl:when test="count(./ViewField)&gt;1">
						<xsl:apply-templates select="./ViewField" mode="NoTableNoLabel"/>
					</xsl:when>
					<xsl:otherwise>
						<xsl:if test="$nd/@type='money'">USD </xsl:if>
						<xsl:apply-templates select="." mode="NoTableNoLabel"/>
						<xsl:apply-templates select="./ViewField" mode="NoTableNoLabel"/>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</xsl:template>
	<xsl:template match="ViewField" mode="NoTableNoLabel">
		<xsl:variable name="nametolookup" select="@name"/>
		<xsl:variable name="nd" select="/Module/ModuleFields/*[@name=$nametolookup]"/>
		<xsl:variable name="linkfield" select="@link"/>
		<xsl:variable name="lnd" select="/Module/ModuleFields/*[@name=$linkfield]"/>
		<xsl:choose>
			<xsl:when test="@link">
				<xsl:choose>
					<xsl:when test="contains($lnd/@sample, '@')">
						<a href="mailto:{$lnd/@sample}">
							<xsl:value-of select="$nd/@sample"/>
						</a>
					</xsl:when>
					<xsl:otherwise>
						<a href="http://{$lnd/@sample}" target="_blank">
							<xsl:value-of select="$nd/@sample"/>
						</a>
					</xsl:otherwise>
				</xsl:choose>
			</xsl:when>
			<xsl:otherwise>
				<xsl:value-of select="$nd/@sample"/>
			</xsl:otherwise>
		</xsl:choose>
		<xsl:text disable-output-escaping="yes">&amp;nbsp;</xsl:text>
	</xsl:template>
	<xsl:template match="EditField">
		<xsl:variable name="nametolookup" select="@name"/>
		<xsl:variable name="nd" select="/Module/ModuleFields/*[@name=$nametolookup]"/>
		<tr>
			<td class="lbl" width="30%" title="{substring-after($nd/@phrase, '|')}"><xsl:value-of select="substring-before($nd/@phrase, '|')"/>:</td>
			<td class="fld" width="70%" title="{substring-after($nd/@phrase, '|')}">
				<input type="text" name="{@name}" value="{$nd/@sample}" size="{@size}" maxlength="{@maxLength}" class="normal"/>
			</td>
		</tr>
	</xsl:template>
	<xsl:template match="MoneyField">
		<xsl:variable name="nametolookup" select="@name"/>
		<xsl:variable name="nd" select="/Module/ModuleFields/*[@name=$nametolookup]"/>
		<tr>
			<td class="lbl" width="30%" title="{substring-after($nd/@phrase, '|')}"><xsl:value-of select="substring-before($nd/@phrase, '|')"/>:</td>
			<td class="fld" width="70%" title="{substring-after($nd/@phrase, '|')}">
				<select name="{@localCurrencyIDField}" class="normal">
					<option id="1">USD</option>
					<option selected="true" id="2">EUR</option>
					<option id="3">GBP</option>
					<option id="4">CDN</option>
				</select>
				<input type="text" name="{@name}" value="{$nd/@sample}" size="{@size}" maxlength="{@maxLength}" class="normal"/>
				<xsl:apply-templates select="./*" mode="NoTableNoLabel"/>
			</td>
		</tr>
	</xsl:template>
	<xsl:template match="MemoField">
		<xsl:variable name="nametolookup" select="@name"/>
		<xsl:variable name="nd" select="/Module/ModuleFields/*[@name=$nametolookup]"/>
		<tr>
			<td class="lbl" width="30%" title="{substring-after($nd/@phrase, '|')}"><xsl:value-of select="substring-before($nd/@phrase, '|')"/>:</td>
			<td class="fld" width="70%" title="{substring-after($nd/@phrase, '|')}">
				<textarea name="{@name}" rows="{@rows}" cols="{@cols}" class="normal">
					<xsl:value-of select="$nd/@sample"/>
				</textarea>
			</td>
		</tr>
	</xsl:template>
	<xsl:template match="EditPlusCodeField">
		<xsl:variable name="nametolookup" select="@name"/>
		<xsl:variable name="nd" select="/Module/ModuleFields/*[@name=$nametolookup]"/>
		<tr>
			<td class="lbl" width="30%" title="{substring-after($nd/@phrase, '|')}"><xsl:value-of select="substring-before($nd/@phrase, '|')"/>:</td>
			<td class="fld" width="70%" title="{substring-after($nd/@phrase, '|')}">
				<input type="text" name="{@name}" value="{$nd/@sample}" size="{@size}" maxlength="{@maxLength}" class="normal"/>
				<select name="{@codeFieldName}" class="normal">
					<xsl:apply-templates select="SampleItem"/>
				</select>
				<!-- codeFieldName="TempUnitsID" codeTypeID="999" -->
			</td>
		</tr>
	</xsl:template>
	<xsl:template match="SampleItem">
		<xsl:variable name="nametolookup" select="../@name"/>
		<xsl:variable name="nd" select="/Module/ModuleFields/*[@name=$nametolookup]"/>
		<xsl:choose>
			<xsl:when test="$nd/@sample = @ID">
				<option id="{@ID}" selected="true">
					<xsl:value-of select="@name"/>
				</option>
			</xsl:when>
			<xsl:otherwise>
				<option id="{@ID}">
					<xsl:value-of select="@name"/>
				</option>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
	<xsl:template match="SampleItem" mode="organization">
		<xsl:variable name="nametolookup" select="../@name"/>
		<xsl:variable name="nd" select="/Module/ModuleFields/*[@name=$nametolookup]"/>
		<xsl:choose>
			<xsl:when test="$nd/@sample = @ID">
				<option id="{@orgID}" selected="true">
					<xsl:value-of select="@orgName"/>
				</option>
			</xsl:when>
			<xsl:otherwise>
				<option id="{@orgID}">
					<xsl:value-of select="@orgName"/>
				</option>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
	<xsl:template match="SampleItem" mode="person">
		<xsl:variable name="nametolookup" select="../@name"/>
		<xsl:variable name="nd" select="/Module/ModuleFields/*[@name=$nametolookup]"/>
		<xsl:choose>
			<xsl:when test="$nd/@sample = @ID">
				<option id="{@ID}" selected="true">
					<xsl:value-of select="@name"/>
				</option>
			</xsl:when>
			<xsl:otherwise>
				<option id="{@ID}">
					<xsl:value-of select="@name"/>
				</option>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
	<xsl:template match="SampleItem" mode="radio">
		<xsl:variable name="nametolookup" select="../@name"/>
		<xsl:variable name="nd" select="/Module/ModuleFields/*[@name=$nametolookup]"/>
		<xsl:choose>
			<xsl:when test="$nd/@sample = @ID">
				<input type="radio" id="{concat($nd/@name, @ID)}" name="{$nd/@name}" value="{@ID}" checked="true"/>
			</xsl:when>
			<xsl:otherwise>
				<input type="radio" id="{concat($nd/@name, @ID)}" name="{$nd/@name}" value="{@ID}"/>
			</xsl:otherwise>
		</xsl:choose>
		<label for="{concat($nd/@name, @ID)}">
			<xsl:value-of select="@name"/>
			<br/>
		</label>
	</xsl:template>
	<xsl:template match="RadioField|CodeRadioField">
		<xsl:variable name="nametolookup" select="@name"/>
		<xsl:variable name="nd" select="/Module/ModuleFields/*[@name=$nametolookup]"/>
		<tr>
			<td class="lbl" width="30%" title="{substring-after($nd/@phrase, '|')}"><xsl:value-of select="substring-before($nd/@phrase, '|')"/>:</td>
			<td class="fld" width="70%" title="{substring-after($nd/@phrase, '|')}">
				<xsl:apply-templates select="SampleItem" mode="radio"/>
			</td>
		</tr>
	</xsl:template>
	<xsl:template match="ComboField|CodeComboField">
		<xsl:variable name="nametolookup" select="@name"/>
		<xsl:variable name="nd" select="/Module/ModuleFields/*[@name=$nametolookup]"/>
		<tr>
			<td class="lbl" width="30%" title="{substring-after($nd/@phrase, '|')}"><xsl:value-of select="substring-before($nd/@phrase, '|')"/>:</td>
			<td class="fld" width="70%" title="{substring-after($nd/@phrase, '|')}">
				<xsl:choose>
					<xsl:when test="@findMode = 'text'">
						<input type="text" name="find@name" value="[text]" size="5" class="normal"/>
					</xsl:when>
					<xsl:when test="@findMode = 'alpha'">
						<input type="text" name="find@name" value="[alpha]" size="5" class="normal"/>
					</xsl:when>
				</xsl:choose>
				<select name="{@name}" class="normal">
					<xsl:apply-templates select="SampleItem"/>
				</select>
			</td>
		</tr>
	</xsl:template>
	<xsl:template match="ComboField|CodeComboField" mode="NoTableNoLabel">
		<xsl:choose>
			<xsl:when test="@findMode = 'text'">
				<input type="text" name="find@name" value="[text]" size="5" class="normal"/>
			</xsl:when>
			<xsl:when test="@findMode = 'alpha'">
				<input type="text" name="find@name" value="[alpha]" size="5" class="normal"/>
			</xsl:when>
		</xsl:choose>
		<select name="{@name}" class="normal">
			<xsl:apply-templates select="SampleItem"/>
		</select>
	</xsl:template>
	<xsl:template match="PersonComboField">
		<xsl:variable name="nametolookup" select="@name"/>
		<xsl:variable name="nd" select="/Module/ModuleFields/*[@name=$nametolookup]"/>
		<tr>
			<td class="lbl" width="30%" title="{substring-after($nd/@phrase, '|')}"><xsl:value-of select="substring-before($nd/@phrase, '|')"/>:</td>
			<td class="fld" width="70%" title="{substring-after($nd/@phrase, '|')}">
				<select name="{@name}" class="normal">
					<xsl:apply-templates select="SampleItem" mode="organization">
						<xsl:sort select="@orgName"/>
						<xsl:sort select="@name"/>
					</xsl:apply-templates>
				</select>
				<br/>
				<xsl:choose>
					<xsl:when test="@findMode = 'text'">
						<input type="text" name="find@name" value="[text]" size="5" class="normal"/>
					</xsl:when>
					<xsl:when test="@findMode = 'alpha'">
						<input type="text" name="find@name" value="[alpha]" size="5" class="normal"/>
					</xsl:when>
				</xsl:choose>
				<select name="{@name}" class="normal">
					<option value="0"/>
					<xsl:apply-templates select="SampleItem" mode="person"/>
				</select>
			</td>
		</tr>
	</xsl:template>
	<xsl:template match="CheckBoxField">
		<xsl:variable name="nametolookup" select="@name"/>
		<xsl:variable name="nd" select="/Module/ModuleFields/*[@name=$nametolookup]"/>
		<tr>
			<td class="lbl" width="30%" title="{substring-after($nd/@phrase, '|')}"><xsl:value-of select="substring-before($nd/@phrase, '|')"/>:</td>
			<td class="fld" width="70%" title="{substring-after($nd/@phrase, '|')}">
				<xsl:choose>
					<xsl:when test="$nd/@sample = 'Yes'">
						<input type="radio" id="{concat($nd/@name, '0')}" name="{$nd/@name}" value="0" checked="true"/>
						<label for="{concat($nd/@name, '0')}">Yes</label>
						<input type="radio" id="{concat($nd/@name, '1')}" name="{$nd/@name}" value="1"/>
						<label for="{concat($nd/@name, '1')}">No</label>
					</xsl:when>
					<xsl:otherwise>
						<input type="radio" id="{concat($nd/@name, '0')}" name="{$nd/@name}" value="0"/>
						<label for="{concat($nd/@name, '0')}">Yes</label>
						<input type="radio" id="{concat($nd/@name, '1')}" name="{$nd/@name}" value="1" checked="true"/>
						<label for="{concat($nd/@name, '1')}">No</label>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</xsl:template>
	<xsl:template match="ViewGrid">
		<tr>
			<td colspan="2">
				<br/>
				<H3 align="left">
					<xsl:value-of select="@phrase"/>
				</H3>
				<table align="left">
					<tr>
						<xsl:for-each select="ViewGridField">
							<td class="lbl" title="{substring-after(@phrase, '|')}">
								<xsl:choose>
									<xsl:when test="contains(@phrase, '|')">
										<xsl:value-of select="substring-before(@phrase, '|')"/>
									</xsl:when>
									<xsl:otherwise>
										<xsl:value-of select="@phrase"/>
									</xsl:otherwise>
								</xsl:choose>
							</td>
						</xsl:for-each>
					</tr>
					<tr>
						<xsl:apply-templates select="ViewGridField"/>
					</tr>
				</table>
			</td>
		</tr>
	</xsl:template>
	<xsl:template match="EditGrid">
		<tr>
			<td colspan="2">
				<br/>
				<H3 align="left">
					<xsl:value-of select="@phrase"/>
				</H3>
			</td>
		</tr>
		<xsl:apply-templates select="GridForm"/>
		<tr>
			<td>
				<table align="left">
					<tr>
						<xsl:for-each select="ViewGridField|EditGridField|MemoGridField|ComboGridField|PersonComboGridField|CodeComboGridField|CheckBoxGridField">
							<td class="lbl" title="{substring-after(@phrase, '|')}">
								<xsl:choose>
									<xsl:when test="contains(@phrase, '|')">
										<xsl:value-of select="substring-before(@phrase, '|')"/>
									</xsl:when>
									<xsl:otherwise>
										<xsl:value-of select="@phrase"/>
									</xsl:otherwise>
								</xsl:choose>
							</td>
						</xsl:for-each>
						<td class="lbl"/>
					</tr>
					<tr>
						<xsl:apply-templates select="ViewGridField|EditGridField|MemoGridField|ComboGridField|PersonComboGridField|CodeComboGridField|CheckBoxGridField"/>
						<td class="fld">
							<a href="#Save">Save</a>
							<br/>
							<a href="#Delete">Delete</a>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</xsl:template>
	<xsl:template match="GridForm">
		<tr>
			<td>
				<table align="left">
					<xsl:apply-templates select="ViewGridField|EditGridField|MemoGridField|ComboGridField|PersonComboGridField|CodeComboGridField|CheckBoxGridField" mode="form"/>
					<tr>
						<td colspan="2" align="right" class="fld">
							<input type="button" name="Save" value="Save"/>
							<input type="button" name="Delete" value="Delete"/>
							<input type="button" name="Cancel" value="Cancel"/>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</xsl:template>
	<xsl:template match="ViewGridField|EditGridField|MemoGridField|PersonComboGridField|CheckBoxGridField" mode="form">
		<xsl:value-of select="@name"/>
		<br/>
	</xsl:template>
	<xsl:template match="CheckGrid|CodeCheckGrid">
		<tr>
			<td colspan="2">
				<br/>
				<H3 align="left">
					<xsl:value-of select="@phrase"/>
				</H3>
				<table align="left">
					<tr>
						<xsl:for-each select="ViewGridField|CheckBoxGridField">
							<td class="lbl" title="{substring-after(@phrase, '|')}">
								<xsl:choose>
									<xsl:when test="contains(@phrase, '|')">
										<xsl:value-of select="substring-before(@phrase, '|')"/>
									</xsl:when>
									<xsl:otherwise>
										<xsl:value-of select="@phrase"/>
									</xsl:otherwise>
								</xsl:choose>
							</td>
						</xsl:for-each>
						<td class="lbl"/>
					</tr>
					<tr>
						<xsl:apply-templates select="ViewGridField|CheckBoxGridField"/>
						<td class="fld">
							<a href="#Save">Save</a>
							<br/>
							<a href="#Delete">Delete</a>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</xsl:template>
	<xsl:template match="ViewGridField">
		<td class="fld">
			<xsl:choose>
				<xsl:when test="@link">
					<xsl:choose>
						<xsl:when test="contains(@linkSample, '@')">
							<a href="mailto:{@linkSample}">
								<xsl:value-of select="@sample"/>
							</a>
						</xsl:when>
						<xsl:otherwise>
							<a href="http://{@linkSample}" target="_blank">
								<xsl:value-of select="@sample"/>
							</a>
						</xsl:otherwise>
					</xsl:choose>
				</xsl:when>
				<xsl:otherwise>
					<xsl:value-of select="@sample"/>
				</xsl:otherwise>
			</xsl:choose>
		</td>
	</xsl:template>
	<xsl:template match="ViewGridField" mode="form">
		<tr>
			<xsl:choose>
				<xsl:when test="contains(@phrase, '|')">
					<td class="lbl" width="30%" title="{substring-after(@phrase, '|')}"><xsl:value-of select="substring-before(@phrase, '|')"/>:</td>
					<td class="fld" width="70%" title="{substring-after(@phrase, '|')}">
						<xsl:choose>
							<xsl:when test="@link">
								<xsl:choose>
									<xsl:when test="contains(@linkSample, '@')">
										<a href="mailto:{@linkSample}">
											<xsl:value-of select="@sample"/>
										</a>
									</xsl:when>
									<xsl:otherwise>
										<a href="http://{@linkSample}" target="_blank">
											<xsl:value-of select="@sample"/>
										</a>
									</xsl:otherwise>
								</xsl:choose>
							</xsl:when>
							<xsl:otherwise>
								<xsl:value-of select="@sample"/>
							</xsl:otherwise>
						</xsl:choose>
					</td>
				</xsl:when>
				<xsl:otherwise>
					<td class="lbl" width="30%" title="{@phrase}"><xsl:value-of select="@phrase"/>:</td>
					<td class="fld" width="70%" title="{@phrase}">
						<xsl:choose>
							<xsl:when test="@link">
								<xsl:choose>
									<xsl:when test="contains(@linkSample, '@')">
										<a href="mailto:{@linkSample}">
											<xsl:value-of select="@sample"/>
										</a>
									</xsl:when>
									<xsl:otherwise>
										<a href="http://{@linkSample}" target="_blank">
											<xsl:value-of select="@sample"/>
										</a>
									</xsl:otherwise>
								</xsl:choose>
							</xsl:when>
							<xsl:otherwise>
								<xsl:value-of select="@sample"/>
							</xsl:otherwise>
						</xsl:choose>
					</td>
				</xsl:otherwise>
			</xsl:choose>
		</tr>
	</xsl:template>
	<xsl:template match="EditGridField">
		<td class="fld">
			<input type="text" name="{@name}" value="{@sample}" size="{@size}" maxlength="{@maxLength}" class="normal"/>
		</td>
	</xsl:template>
	<xsl:template match="EditGridField" mode="form">
		<tr>
			<xsl:choose>
				<xsl:when test="contains(@phrase, '|')">
					<td class="lbl" width="30%" title="{substring-after(@phrase, '|')}"><xsl:value-of select="substring-before(@phrase, '|')"/>:</td>
					<td class="fld" width="70%" title="{substring-after(@phrase, '|')}">
						<input type="text" name="{@name}" value="{@sample}" size="{@size}" maxlength="{@maxLength}" class="normal"/>
					</td>
				</xsl:when>
				<xsl:otherwise>
					<td class="lbl" width="30%" title="{@phrase}"><xsl:value-of select="@phrase"/>:</td>
					<td class="fld" width="70%" title="{@phrase}">
						<input type="text" name="{@name}" value="{@sample}" size="{@size}" maxlength="{@maxLength}" class="normal"/>
					</td>
				</xsl:otherwise>
			</xsl:choose>
		</tr>
	</xsl:template>
	<xsl:template match="MemoGridField" mode="form">
		<tr>
			<xsl:choose>
				<xsl:when test="contains(@phrase, '|')">
					<td class="lbl" width="30%" title="{substring-after(@phrase, '|')}"><xsl:value-of select="substring-before(@phrase, '|')"/>:</td>
					<td class="fld" width="70%" title="{substring-after(@phrase, '|')}">
						<textarea name="{@name}" rows="{@rows}" cols="{@cols}" class="normal">
							<xsl:value-of select="@sample"/>
						</textarea>
					</td>
				</xsl:when>
				<xsl:otherwise>
					<td class="lbl" width="30%" title="{@phrase}"><xsl:value-of select="@phrase"/>:</td>
					<td class="fld" width="70%" title="{@phrase}">
						<textarea name="{@name}" rows="{@rows}" cols="{@cols}" class="normal">
							<xsl:value-of select="@sample"/>
						</textarea>
					</td>
				</xsl:otherwise>
			</xsl:choose>
		</tr>
	</xsl:template>
	<xsl:template match="MemoGridField">
		<td class="fld">
			<textarea name="{@name}" rows="{@rows}" cols="{@cols}" class="normal">
				<xsl:value-of select="@sample"/>
			</textarea>
		</td>
	</xsl:template>
	<xsl:template match="ComboGridField|CodeComboGridField">
		<td class="fld">
			<xsl:choose>
				<xsl:when test="@findMode = 'text'">
					<input type="text" name="find@name" value="[text]" size="5" class="normal"/>
				</xsl:when>
				<xsl:when test="@findMode = 'alpha'">
					<input type="text" name="find@name" value="[alpha]" size="5" class="normal"/>
				</xsl:when>
			</xsl:choose>
			<select name="{@name}" class="normal">
				<xsl:apply-templates select="SampleItem"/>
			</select>
		</td>
	</xsl:template>
	<xsl:template match="ComboGridField|CodeComboGridField" mode="form">
		<tr>
			<xsl:choose>
				<xsl:when test="contains(@phrase, '|')">
					<td class="lbl" width="30%" title="{substring-after(@phrase, '|')}"><xsl:value-of select="substring-before(@phrase, '|')"/>:</td>
					<td class="fld" width="70%" title="{substring-after(@phrase, '|')}">
						<xsl:choose>
							<xsl:when test="@findMode = 'text'">
								<input type="text" name="find@name" value="[text]" size="5" class="normal"/>
							</xsl:when>
							<xsl:when test="@findMode = 'alpha'">
								<input type="text" name="find@name" value="[alpha]" size="5" class="normal"/>
							</xsl:when>
						</xsl:choose>
						<select name="{@name}" class="normal">
							<xsl:apply-templates select="SampleItem"/>
						</select>
					</td>
				</xsl:when>
				<xsl:otherwise>
					<td class="lbl" width="30%" title="{@phrase}"><xsl:value-of select="@phrase"/>:</td>
					<td class="fld" width="70%" title="{@phrase}">
						<xsl:choose>
							<xsl:when test="@findMode = 'text'">
								<input type="text" name="find@name" value="[text]" size="5" class="normal"/>
							</xsl:when>
							<xsl:when test="@findMode = 'alpha'">
								<input type="text" name="find@name" value="[alpha]" size="5" class="normal"/>
							</xsl:when>
						</xsl:choose>
						<select name="{@name}" class="normal">
							<xsl:apply-templates select="SampleItem"/>
						</select>
					</td>
				</xsl:otherwise>
			</xsl:choose>
		</tr>
	</xsl:template>
	<xsl:template match="PersonComboGridField">
		<td class="fld">
			<select name="{@name}" class="normal">
				<option value="0"/>
				<xsl:apply-templates select="SampleItem" mode="organization">
					<xsl:sort select="@orgName"/>
					<xsl:sort select="@name"/>
				</xsl:apply-templates>
			</select>
			<br/>
			<xsl:choose>
				<xsl:when test="@findMode = 'text'">
					<input type="text" name="find@name" value="[text]" size="5" class="normal"/>
				</xsl:when>
				<xsl:when test="@findMode = 'alpha'">
					<input type="text" name="find@name" value="[alpha]" size="5" class="normal"/>
				</xsl:when>
			</xsl:choose>
			<select name="{@name}" class="normal">
				<option value="0"/>
				<xsl:apply-templates select="SampleItem" mode="person"/>
			</select>
		</td>
	</xsl:template>
	<xsl:template match="PersonComboGridField" mode="form">
		<tr>
			<xsl:choose>
				<xsl:when test="contains(@phrase, '|')">
					<td class="lbl" width="30%" title="{substring-after(@phrase, '|')}"><xsl:value-of select="substring-before(@phrase, '|')"/>:</td>
					<td class="fld" width="70%" title="{substring-after(@phrase, '|')}">
						<select name="{@name}" class="normal">
							<option value="0"/>
							<xsl:apply-templates select="SampleItem" mode="organization">
								<xsl:sort select="@orgName"/>
								<xsl:sort select="@name"/>
							</xsl:apply-templates>
						</select>
						<br/>
						<xsl:choose>
							<xsl:when test="@findMode = 'text'">
								<input type="text" name="find@name" value="[text]" size="5" class="normal"/>
							</xsl:when>
							<xsl:when test="@findMode = 'alpha'">
								<input type="text" name="find@name" value="[alpha]" size="5" class="normal"/>
							</xsl:when>
						</xsl:choose>
						<select name="{@name}" class="normal">
							<option value="0"/>
							<xsl:apply-templates select="SampleItem" mode="person"/>
						</select>
					</td>
				</xsl:when>
				<xsl:otherwise>
					<td class="lbl" width="30%" title="{@phrase}"><xsl:value-of select="@phrase"/>:</td>
					<td class="fld" width="70%" title="{@phrase}">
						<select name="{@name}" class="normal">
							<option value="0"/>
							<xsl:apply-templates select="SampleItem" mode="organization">
								<xsl:sort select="@orgName"/>
								<xsl:sort select="@name"/>
							</xsl:apply-templates>
						</select>
						<br/>
						<select name="{@name}" class="normal">
							<option value="0"/>
							<xsl:apply-templates select="SampleItem" mode="person"/>
						</select>
					</td>
				</xsl:otherwise>
			</xsl:choose>
		</tr>
	</xsl:template>
	<xsl:template match="CheckBoxGridField">
		<td class="fld">
			<xsl:choose>
				<xsl:when test="@sample = 'Yes'">
					<input type="radio" id="{concat(@name, '0')}" name="{@name}" value="0" checked="true"/>
					<label for="{concat(@name, '0')}">Yes</label>
					<input type="radio" id="{concat(@name, '1')}" name="{@name}" value="1"/>
					<label for="{concat(@name, '1')}">No</label>
				</xsl:when>
				<xsl:otherwise>
					<input type="radio" id="{concat(@name, '0')}" name="{@name}" value="0"/>
					<label for="{concat(@name, '0')}">Yes</label>
					<input type="radio" id="{concat(@name, '1')}" name="{@name}" value="1" checked="true"/>
					<label for="{concat(@name, '1')}">No</label>
				</xsl:otherwise>
			</xsl:choose>
		</td>
	</xsl:template>
	<xsl:template match="CheckBoxGridField" mode="form">
		<tr>
			<xsl:choose>
				<xsl:when test="contains(@phrase, '|')">
					<td class="lbl" width="30%" title="{substring-after(@phrase, '|')}"><xsl:value-of select="substring-before(@phrase, '|')"/>:</td>
					<td class="fld" width="70%" title="{substring-after(@phrase, '|')}">
						<xsl:choose>
							<xsl:when test="@sample = 'Yes'">
								<input type="radio" id="{concat(@name, '0')}" name="{@name}" value="0" checked="true"/>
					<label for="{concat(@name, '0')}">Yes</label>
					<input type="radio" id="{concat(@name, '1')}" name="{@name}" value="1"/>
					<label for="{concat(@name, '1')}">No</label>
							</xsl:when>
							<xsl:otherwise>
								<input type="radio" id="{concat(@name, '0')}" name="{@name}" value="0"/>
					<label for="{concat(@name, '0')}">Yes</label>
					<input type="radio" id="{concat(@name, '1')}" name="{@name}" value="1" checked="true"/>
					<label for="{concat(@name, '1')}">No</label>
							</xsl:otherwise>
						</xsl:choose>
					</td>
				</xsl:when>
				<xsl:otherwise>
					<td class="lbl" width="30%" title="{@phrase}"><xsl:value-of select="@phrase"/>:</td>
					<td class="fld" width="70%" title="{@phrase}">
						<xsl:choose>
							<xsl:when test="@sample = 'Yes'">
								<input type="radio" id="{concat(@name, '0')}" name="{@name}" value="0" checked="true"/>
					<label for="{concat(@name, '0')}">Yes</label>
					<input type="radio" id="{concat(@name, '1')}" name="{@name}" value="1"/>
					<label for="{concat(@name, '1')}">No</label>
							</xsl:when>
							<xsl:otherwise>
								<input type="radio" id="{concat(@name, '0')}" name="{@name}" value="0"/>
					<label for="{concat(@name, '0')}">Yes</label>
					<input type="radio" id="{concat(@name, '1')}" name="{@name}" value="1" checked="true"/>
					<label for="{concat(@name, '1')}">No</label>
							</xsl:otherwise>
						</xsl:choose>
					</td>
				</xsl:otherwise>
			</xsl:choose>
			
			
			
			
		</tr>
	</xsl:template>
	<xsl:template match="ListFields">
		<table width="80%" align="center" bgcolor="#0193a6">
			<tr>
				<td colspan="2">
					<br/>
					<H3 align="left"><xsl:value-of select="../@name"/> List</H3>
					<table align="left" cellpadding="2">
						<tr>
							<xsl:for-each select="ListField">
								<td class="lbl" title="{substring-after(@phrase, '|')}">
									<xsl:choose>
										<xsl:when test="contains(@phrase, '|')">
											<xsl:value-of select="substring-before(@phrase, '|')"/>
										</xsl:when>
										<xsl:otherwise>
											<xsl:value-of select="@phrase"/>
										</xsl:otherwise>
									</xsl:choose>
								</td>
							</xsl:for-each>
							<td class="lbl"/>
						</tr>
						<tr>
							<xsl:apply-templates select="ListField"/>
							<td class="fld">
								<a href="#View">View</a>
							</td>
						</tr>
						<tr>
							<td class="lbl" colspan="{count(ListField)+1}">
							[&lt;- Previous] <span class="normal"> Page 1 of 1 </span> [Next ->] </td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</xsl:template>
	<xsl:template match="ListField">
		<xsl:variable name="nametolookup" select="@name"/>
		<xsl:variable name="nd" select="/Module/ModuleFields/*[@name=$nametolookup]"/>
		<xsl:variable name="linkfield" select="@link"/>
		<xsl:variable name="lnd" select="/Module/ModuleFields/*[@name=$linkfield]"/>
		<td class="fld">
			<xsl:choose>
				<xsl:when test="@link">
					<xsl:choose>
						<xsl:when test="contains($lnd/@sample, '@')">
							<a href="mailto:{$lnd/@sample}">
								<xsl:value-of select="$nd/@sample"/>
							</a>
						</xsl:when>
						<xsl:otherwise>
							<a href="http://{$lnd/@sample}" target="_blank">
								<xsl:value-of select="$nd/@sample"/>
							</a>
						</xsl:otherwise>
					</xsl:choose>
				</xsl:when>
				<xsl:otherwise>
					<xsl:value-of select="$nd/@sample"/>
				</xsl:otherwise>
			</xsl:choose>
		</td>
	</xsl:template>
	<xsl:template match="Documentation">
		<br/>
		<br/>
		<table width="80%" align="center" bgcolor="#FFFFFF">
			<tr>
				<td>
					<H2 style="color:'#000000'" align="left">Documentation</H2>
					<xsl:apply-templates select="./*"/>
				</td>
			</tr>
		</table>
	</xsl:template>
	<xsl:template match="Introduction|Implementation|PeopleElements|DataCollection|DataInput|OrganizationalInfo|OrganizationalLearning|Training|ApplyKnowledge|FAQ">
		<H3 style="color:'#000000'" align="left">
			<xsl:value-of select="@title"/>
		</H3>
		<span class="normal">
			<xsl:value-of select="."/>
		</span>
	</xsl:template>
	<xsl:template match="Module">
		<HTML>
			<HEAD>
				<TITLE>
					<xsl:value-of select="@name"/>
				</TITLE>
				<style>
				.normal { 
					font-family: Verdana, Arial, Helvetica, sans-serif; 
					font-size: 11px;
					font-weight: normal;
				}
				H1 {  
					font-family: Verdana, Arial, Helvetica, sans-serif; 
					font-size: 28px; 
					font-weight: normal;
				}
				H2 {  
					font-family: Verdana, Arial, Helvetica, sans-serif; 
					font-size: 20px; 
					font-weight: normal;
					color: #ffffff					
				}		
				H3 {  
					font-family: Verdana, Arial, Helvetica, sans-serif; 
					font-size: 16px; 
					font-weight: bold;
					color: #ffffff					
				}					
				H4 {  
					font-family: Verdana, Arial, Helvetica, sans-serif; 
					font-size: 12px; 
					font-weight: bold;
					text-align: center;
					color: #028293
				} 
				.lbl {
					background-color: #FDF998;
					font-weight: bold;
					font-size: 11px;
				}
				.fld {
					background-color: #ffffff;
					font-size: 11px;
				}
		 </style>
			</HEAD>
			<BODY class="normal" bgcolor="#8DBCC2">
				<H1 align="center">
					Screens of the <xsl:value-of select="@name"/> Module</H1>
				<H4>CONFIDENTIAL MATERIALS - TRADE SECRET<br/>
				Copyright 2003 - Emprise, Inc.</H4>
				<xsl:apply-templates select="Screens/*"/>
				<xsl:apply-templates select="ListFields"/>
				<xsl:apply-templates select="Documentation"/>
			</BODY>
		</HTML>
	</xsl:template>
</xsl:stylesheet>
