ABC SilverStripe Library
========================

<!-- PROJECT SHIELDS -->
[![SonarCloud](https://github.com/catch-oss/abc-silverstripe/actions/workflows/sonar.yml/badge.svg)](https://github.com/catch-oss/abc-silverstripe/actions/workflows/sonar.yml)
[![Test](https://github.com/catch-oss/abc-silverstripe/actions/workflows/test.yml/badge.svg)](https://github.com/catch-oss/abc-silverstripe/actions/workflows/test.yml)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=catch-design_catch-oss-abc-silverstripe&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=catch-design_catch-oss-abc-silverstripe)
[![Bugs](https://sonarcloud.io/api/project_badges/measure?project=catch-design_catch-oss-abc-silverstripe&metric=bugs)](https://sonarcloud.io/component_measures?id=catch-design_catch-oss-abc-silverstripe)
[![Code Smells](https://sonarcloud.io/api/project_badges/measure?project=catch-design_catch-oss-abc-silverstripe&metric=code_smells)](https://sonarcloud.io/component_measures?id=catch-design_catch-oss-abc-silverstripe)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=catch-design_catch-oss-abc-silverstripe&metric=coverage)](https://sonarcloud.io/component_measures?id=catch-design_catch-oss-abc-silverstripe)
[![Duplicated Lines Density](https://sonarcloud.io/api/project_badges/measure?project=catch-design_catch-oss-abc-silverstripe&metric=duplicated_lines_density)](https://sonarcloud.io/component_measures?id=catch-design_catch-oss-abc-silverstripe)
[![Lines of Code](https://sonarcloud.io/api/project_badges/measure?project=catch-design_catch-oss-abc-silverstripe&metric=ncloc)](https://sonarcloud.io/component_measures?id=catch-design_catch-oss-abc-silverstripe)
[![Reliability Rating](https://sonarcloud.io/api/project_badges/measure?project=catch-design_catch-oss-abc-silverstripe&metric=reliability_rating)](https://sonarcloud.io/component_measures?id=catch-design_catch-oss-abc-silverstripe)
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=catch-design_catch-oss-abc-silverstripe&metric=security_rating)](https://sonarcloud.io/component_measures?id=catch-design_catch-oss-abc-silverstripe)
[![Technical Debt](https://sonarcloud.io/api/project_badges/measure?project=catch-design_catch-oss-abc-silverstripe&metric=sqale_index)](https://sonarcloud.io/component_measures?id=catch-design_catch-oss-abc-silverstripe)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=catch-design_catch-oss-abc-silverstripe&metric=sqale_rating)](https://sonarcloud.io/component_measures?id=catch-design_catch-oss-abc-silverstripe)
[![Vulnerabilities](https://sonarcloud.io/api/project_badges/measure?project=catch-design_catch-oss-abc-silverstripe&metric=vulnerabilities)](https://sonarcloud.io/component_measures?id=catch-design_catch-oss-abc-silverstripe)



What's in this thing Anyway?
----------------------------



This is a base library that is required by some of the other abc modules, it
includes a few things, some of the more useful features are:



### Enhanced requirements handling

Allows for more granular inclusion of dependencies meaning you can more easily
block front end dependencies from the CMS and fixes some issues with x-include
headers in the security ping



### Basic Utility Classes

-   Zero config PDO based DB abstraction layer for when the ORM doesn't do what
    you need it to

-   DataObjectHelper for extracting meta data from the ORM

-   String and URL manipulation classes



### Extensions

-   Image

-   File



### Form Fields

-   SyntaxHighlightedField - extends a basic text area with syntax highlighting

-   ColourPickerField


License
-------


Copyright (c) 2015, azt3k
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

1. Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.

2. Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.

3. Neither the name of the copyright holder nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
